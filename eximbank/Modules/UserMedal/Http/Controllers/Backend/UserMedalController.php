<?php

namespace Modules\UserMedal\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\UserMedal\Entities\UserMedal;
use Modules\UserMedal\Entities\UserMedalSettings;
use Modules\UserMedal\Entities\UserMedalSettingsItems;
use Modules\UserMedal\Entities\UserMedalResult;
use App\Models\Profile;

class UserMedalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $title = trans('lamenu.compete_title');
        return view('usermedal::backend.usermedal.index',["title"=>$title]);
    }

    public function getData(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $query = UserMedal::where("parent_id","=",0);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
                $row->status =$row->status==1?trans('labutton.enable'):trans('labutton.disable');
                $row->createdby = Profile::fullname($row->updated_by);
                $row->createdat = get_date($row->created_at, 'd/m/Y');
                $row->edit_url = route('module.usermedal.edit',["id"=>$row->id]);
                $row->vphoto = '<img src="'.image_file($row->photo).'" class="w-100">';
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function getDataChild($parent_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $query = UserMedal::where("parent_id","=",$parent_id);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->vphoto = '<img src="'.image_file($row->photo).'" class="w-100">';
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null)
    {
        $ro = false;
        $model = UserMedal::firstOrNew(["id"=>$id]);

        $items = UserMedalSettingsItems::where('usermedal_id', '=', $id)->get()->pluck("id")->toArray();

        $totalrs=UserMedalResult::whereIn("settings_items_id",$items)->count();
        if($totalrs>0) $ro = true;

        if($id){
            $model->childs = UserMedal::where("parent_id","=",$id)->get();
        }

        return view('usermedal::backend.usermedal.form', [
            'model' => $model,
            'ro' => $ro,
        ]);
    }

    public function save(Request $request)
    {
        if($request->id) {
            $id = $request->id;
        }else if($request->idc) {
            $id = $request->idc;
        }else {
            $id = 0;
        }

        if ($id){
            $this->validateRequest([
                'code' => 'required_if:id,==,|unique:el_usermedal,code,'.$id,
                'name' => 'required',
            ], $request, [
                'name' => trans('backend.name')
            ]);
        }
        else {
            $this->validateRequest([
                'code' => 'required_if:id,==,|unique:el_usermedal,code,'.$id,
                'name' => 'required',
                'photo' => 'required',
            ], $request, [
                'name' => trans('backend.name'),
                'photo' => trans("latraining.picture"),
            ]);
        }

        if ($id) {
            $model = UserMedal::firstOrNew(['id' => $id]);
            $model->fill($request->all());
            if($request->parent_id){
                $parent = UserMedal::find($request->parent_id);
                $model->code = $parent->code.'_'.$request->code;
            }

            if($request->photo){
                $model->photo = upload_image([600,400], $request->photo);
            }else {
                unset($model->photo);
            }
            $model->save();

            if($request->parent_id){
                $id = $request->parent_id;
            }

            return \response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.usermedal.edit',$id)
            ]);
        }
        else {
            $model = UserMedal::firstOrNew(['id' => $id]);
            $model->fill($request->all());

            if($request->parent_id){
                $parent = UserMedal::find($request->parent_id);
                $model->code = $parent->code.'_'.$request->code;
            }

            if($request->photo)
            $model->photo = upload_image([600,400],$request->photo);
            $model->save();

            if($request->parent_id)
                $id = $request->parent_id;
            else $id = $model->id;

            return \response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.usermedal.edit',$id)
            ]);

        }

    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function remove(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required|array',
        ]);

        $ids = $request->post('ids');
        $check = UserMedalSettings::whereIn('usermedal_id', $ids)->exists();
        if ($check) {
            json_message('Không thể xóa', 'error');
        }
        UserMedal::destroy($ids);

        return response()->json([
            'status' => 'success'
        ]);
    }


    public function editPromotionChild(Request $request){
        $id = $request->id;
        $medal = UserMedal::find($id);

        $code = explode('_', $medal->code);

        if(is_array($code) && isset($code[1])) $code =$code[1];
        else $code=$medal->code;

        $name = $medal->name;
        $rank = $medal->rank;
        $content = $medal->content;
        $image_view = '<img src="'.image_file($medal->photo).'" style="height:100px; width:auto;">';

        return response()->json([
            'image_view' => $image_view,
            'code' => $code,
            'name' => $name,
            'rank' => $rank,
            'content' => $content,
        ]);
    }

}
