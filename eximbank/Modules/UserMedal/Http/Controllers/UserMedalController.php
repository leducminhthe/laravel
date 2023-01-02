<?php

namespace Modules\UserMedal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Quiz\Entities\Quiz;
use Modules\UserMedal\Entities\UserMedal;
use Modules\UserMedal\Entities\UserMedalSettings;
use Modules\UserMedal\Entities\UserMedalSettingsItems;
use Modules\UserMedal\Entities\UserMedalResult;
use App\Models\Profile;
use App\Scopes\CompanyScope;
use Modules\UserMedal\Entities\UserMedalCompleted;

class UserMedalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'start_date');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');

        UserMedalSettings::addGlobalScope(new CompanyScope());
        $query = UserMedalSettings::query();
        $query->select('el_usermedal_settings.*', 'b.name', 'b.content', 'b.photo');
        $query->join('el_usermedal AS b', 'b.id', 'el_usermedal_settings.usermedal_id');
        $query->where("el_usermedal_settings.status","=",1);
        if ($search) {
            $query->where('el_usermedal_settings.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        return view('usermedal::frontend.list',["items"=>$rows]);
    }

    public function history(Request $request)
    {
        return view('usermedal::frontend.history',[]);
    }

    public function getDataHistory(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');

        $query = UserMedalCompleted::query();
        $query->from('el_usermedal_completed AS a');
        $query->select([
            'a.id',
            'a.user_id',
            'a.created_at',
            'c.name as submedal_name',
            'c.rank as submedal_rank',
            'd.name',
        ]);
        $query->join('el_usermedal_settings_items AS b', 'b.id', 'a.settings_items_id_got');
        $query->leftJoin('el_usermedal as c', 'c.id', '=', 'b.usermedal_id');
        $query->leftJoin('el_usermedal as d', 'd.id', '=', 'c.parent_id')->orderBy('c.rank', 'ASC');
        $query->where("a.user_id","=",profile()->user_id);

        if ($search) {
            $query->where('c.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->datecreated = get_date($row->created_at, 'd/m/Y');
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function detail(Request $request, $id)
    {

        $search = $request->input('search');
        $query = \DB::query();
        $query->select('a.*', 'b.name', 'b.content', 'b.photo', 'b.rule');
        $query->from('el_usermedal_settings AS a');
        $query->join('el_usermedal AS b', 'b.id', 'a.usermedal_id');
        $query->where("a.status","=",1)->where("a.id","=",$id);
        if ($search) {
            $query->where('a.name', 'like', '%'. $search .'%');
        }

        $info = $query->first();

        $item_list = UserMedalSettingsItems::where("setting_id","=",$id)->get()->toArray();

        $arrOnline = array();
        $arrOffline = array();
        $arrQuiz = array();
        $arrMedal = array();

        foreach ($item_list as $item){
            if($item["item_type"]=='2'){
                $online = OnlineCourse::find($item["item_id"]);
                $item["code"]= $online->code;
                $item["name"]= $online->name;
                $arrOnline[]=$item;
            }
            else if($item["item_type"]=='3'){
                $offline = OfflineCourse::find($item["item_id"]);
                $item["code"]= $offline->code;
                $item["name"]= $offline->name;
                $arrOffline[]=$item;
            }
            else if($item["item_type"]=='4'){
                $quiz = Quiz::find($item["item_id"]);
                $item["code"]= $quiz->code;
                $item["name"]= $quiz->name;
                $arrQuiz[]=$item;
            }
            else {
                $medal = UserMedal::find($item["usermedal_id"]);
                $item["photo"]= $medal->photo;
                $item["name"]= $medal->name;
                $item["content"]= $medal->content;
                $item["rank"]= $medal->rank;
                $arrMedal[]=$item;
            }
        }

        return view('usermedal::frontend.detail',[
            "info"=>$info,
            "arrOnline"=>$arrOnline,
            "arrOffline"=>$arrOffline,
            "arrQuiz"=>$arrQuiz,
            "arrMedal"=>$arrMedal
        ]);
    }


    public function getDataResult(Request $request, $id) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $query = UserMedalResult::query();
        $query->from('el_usermedal_completed AS a');
        $query->select('a.*', 'c.name', 'c.content', 'c.photo', 'a.settings_id', 'd.full_name', 'd.code', 'd.email', 'd.title_name', 'd.unit_name');
        $query->join('el_usermedal_settings AS e', 'e.id', 'a.settings_id');
      //  $query->join('el_usermedal_settings_items AS b', 'b.setting_id', 'a.settings_id');
        $query->leftJoin('el_usermedal AS c', 'c.id', 'e.usermedal_id');
        $query->leftJoin('el_profile_view AS d', 'd.user_id', 'a.user_id');

        $query->where("a.settings_id","=",$id)->orderBy('c.rank', 'ASC');
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {

            $query = UserMedalSettingsItems::query();
            $query->select('a.*', 'b.name', 'b.rank');
            $query->from('el_usermedal_settings_items AS a');
            $query->join('el_usermedal AS b', 'b.id', 'a.usermedal_id');
            $query->where("a.id","=",$row->settings_items_id_got);
            $setting_item= $query->first();

            $row->submedal_name = $setting_item->name;
            $row->submedal_rank =$setting_item->rank;
            $row->datecreated = get_date($row->updated_at, 'd/m/Y H:i');

        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

}
