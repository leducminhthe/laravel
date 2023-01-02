<?php

namespace Modules\UserMedal\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\UserMedal\Entities\UserMedal;
use Modules\UserMedal\Entities\UserMedalSettings;
use Modules\UserMedal\Entities\UserMedalSettingsItems;
use App\Models\Profile;
use App\Models\Categories\Subject;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Quiz\Entities\Quiz;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use Modules\UserMedal\Entities\UserMedalObject;
use Modules\UserMedal\Imports\ProfileImport;
use Modules\Quiz\Entities\QuizPart;
use Modules\UserMedal\Entities\UserMedalResult;

class UserMedalSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $title = trans('lamenu.emulation_program');
        return view('usermedal::backend.usermedal-settings.index',["title"=>$title]);
    }

    public function getData(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $search = $request->input('search');
        $query = UserMedalSettings::query();
        $query->select([
            'el_usermedal_settings.*',
            'usermedal.code as usermedal_code',
            'usermedal.name as usermedal_name',
        ]);
        $query->leftJoin('el_usermedal AS usermedal', 'usermedal.id', '=', 'el_usermedal_settings.usermedal_id');

        if ($search) {
            $query->where(function($sub) use ($search){
                $sub->orWhere('usermedal.code', 'like', '%'. $search .'%');
                $sub->orWhere('usermedal.name', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->createdby = Profile::fullname($row->updated_by);
            $row->createdat = get_date($row->created_at, 'd/m/Y');

            if($row->start_date>0)
            $row->start_date = date('d/m/Y', $row->start_date);
            else $row->start_date ='';
            if($row->end_date>0)
            $row->end_date = date('d/m/Y', $row->end_date);
            else $row->end_date ='';

            $row->status =$row->status==1?trans('labutton.enable'):trans('labutton.disable');

            $row->edit_url = route('module.usermedal-setting.edit',["id"=>$row->id]);
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request,$id = null)
    {
        $ro = false;
        $model = UserMedalSettings::firstOrNew(["id"=>$id]);
        $medal = UserMedal::where("parent_id","=","0")->get();
        $subject = Subject::get();

        $items = UserMedalSettingsItems::where("setting_id","=",$id)->get();

        $subMedal = UserMedal::where("parent_id","=",$model->usermedal_id)->orderBy('rank', 'asc')->get();

        $onlineItems = array();
        $offlineItems = array();
        $quizItems = array();
        $subMedalItems = array();

        foreach ($items as $v){
            if(!$ro){
                $ro = UserMedalResult::where("settings_items_id","=",$v->id)->exists();
            }
            if($v->item_type=='2'){
                $item = OnlineCourse::find($v->item_id);
                $v->code= $item->code;
                $v->name= $item->name;
                $onlineItems[]=$v;
            }
            else if($v->item_type=='3'){
                $item = OfflineCourse::find($v->item_id);
                $v->code= $item->code;
                $v->name= $item->name;
                $offlineItems[]=$v;
            }
            else if($v->item_type=='4'){
                $item = Quiz::find($v->item_id);
                $v->code= $item->code;
                $v->name= $item->name;
                $quizItems[]=$v;
            }
            else if($v->item_type=='5'){
                $subMedalItems[$v->usermedal_id]=[$v->min_score, $v->max_score];
            }
        }

        $corporations = Unit::select(['id','name','code'])->where('level', '=', 1)->where('status', '=', 1)->get();
        $title = Titles::select(['id','name','code'])->get();

        return view('usermedal::backend.usermedal-settings.form', [
            'model' => $model,
            'medal' => $medal,
            'submedal' => $subMedal,
            'subjects' => $subject,
            'online_items' => $onlineItems,
            'offline_items' => $offlineItems,
            'quiz_items' => $quizItems,
            'sub_medal_items' => $subMedalItems,
            'tabs' => $request->tabs,
            'corporations' => $corporations,
            'title' => $title,
            'ro' => $ro,
        ]);

    }

    public function save(Request $request)
    {
        if ($request->id){
            $this->validateRequest([
                'usermedal_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'status' => 'required',
            ], $request, [
                'start_date' => trans('latraining.start_date'),
                'end_date' => trans('latraining.end_date'),
                'status' => trans("latraining.status")
            ]);
        }
        else {
            $this->validateRequest([
                'usermedal_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'status' => 'required',
            ], $request, [
                'start_date' => trans('latraining.start_date'),
                'end_date' => trans('latraining.end_date'),
                'status' => trans("latraining.status")
            ]);

        }
        $request->start_date = str_replace('/','-',$request->start_date);
        $request->end_date = str_replace('/','-',$request->end_date);

        $start_date = $request->start_date.' '.$request->start_hour.':'.$request->start_minute.':00';
        $end_date = $request->end_date.' '.$request->end_hour.':'.$request->end_minute.':00';

        if ($request->id) {

            $model = UserMedalSettings::firstOrNew(['id' => $request->id]);
            $model->fill($request->all());
            $model->start_date =strtotime($start_date);
            $model->end_date =strtotime($end_date);
            $model->save();

            if(!empty($request->point_from))
            foreach ($request->point_from as $k=>$v){
                $model = UserMedalSettingsItems::firstOrNew(['setting_id' => $request->id, 'usermedal_id' => $k]);
                $model->item_id =0;
                $model->item_type =5;
                $model->min_score =$v;
                $model->max_score =$request->point_to[$k];
                $model->save();
            }

            return \response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.usermedal-setting.edit',$request->id)
            ]);
        }
        else {

            $model = UserMedalSettings::firstOrNew(['id' => $request->id]);
            $model->fill($request->all());
            $model->start_date =strtotime($start_date);
            $model->end_date =strtotime($end_date);
            $model->save();

            return \response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.usermedal-setting.edit',$model->id)
            ]);

        }

    }

    public function saveItems(Request $request, $type, $form)
    {

        if ($request->settingitem_id){
            $this->validateRequest([
                'item_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ], $request, [
                'start_date' => trans('latraining.start_date'),
                'end_date' => trans('latraining.end_date'),
                'item_id' =>trans('lamenu.course')
            ]);
        }
        else {
            $this->validateRequest([
                'item_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ], $request, [
                'start_date' => trans('latraining.start_date'),
                'end_date' => trans('latraining.end_date'),
                'item_id' =>trans('lamenu.course')
            ]);

        }
        $request->start_date = str_replace('/','-',$request->start_date);
        $request->end_date = str_replace('/','-',$request->end_date);

        $start_date = $request->start_date.' '.$request->start_hour.':'.$request->start_minute.':00';
        $end_date = $request->end_date.' '.$request->end_hour.':'.$request->end_minute.':00';

        $setting = UserMedalSettings::firstOrNew(['id' => $type]);

        if ($request->settingitem_id) {

            $model = UserMedalSettingsItems::firstOrNew(['id' => $request->settingitem_id]);
            $model->fill($request->all());
            $model->start_date =strtotime($start_date);
            $model->end_date =strtotime($end_date);
            $model->save();

            return \response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.usermedal-setting.edit',["id"=>$type])
            ]);
        }
        else {

            $model = new UserMedalSettingsItems();
            $model->fill($request->all());
            $model->setting_id =$type;
            $model->usermedal_id =$setting->usermedal_id;
            $model->item_type =$form;
            $model->start_date =strtotime($start_date);
            $model->end_date =strtotime($end_date);

            $model->save();

            return \response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.usermedal-setting.edit',["id"=>$type])
            ]);

        }

    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function removeItem(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required|array',
        ]);

        $ids = $request->post('ids');
        UserMedalSettingsItems::destroy($ids);

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function remove(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required|array',
        ]);

        $ids = $request->post('ids');
        UserMedalSettings::destroy($ids);

        return response()->json([
            'status' => 'success'
        ]);
    }


    public function editItem(Request $request){
        $id = $request->id;

        $medal = UserMedalSettingsItems::find($id);

        $start_date = date('d/m/Y',$medal->start_date);
        $start_hour = date('H',$medal->start_date);
        $start_minute = date('i',$medal->start_date);
        $end_date = date('d/m/Y',$medal->end_date);
        $end_hour = date('H',$medal->end_date);
        $end_minute = date('i',$medal->end_date);

        if($medal->item_type=="2")
            $item = OnlineCourse::find($medal->item_id);
        else if($medal->item_type=="3")
            $item = OfflineCourse::find($medal->item_id);
        else if($medal->item_type=="4")
            $item = Quiz::find($medal->item_id);

        return response()->json([
            'id' => $medal->id,
            'start_date' => $start_date,
            'start_hour' => $start_hour,
            'start_minute' => $start_minute,
            'end_date' => $end_date,
            'end_hour' => $end_hour,
            'end_minute' => $end_minute,
            'subject' => $item->subject_id,
            'item' => $item->id,
            'type' => $medal->item_type,
        ]);
    }


    public function loadCourses(Request $request) {
        $search = $request->search;
        $subject_id = (int) $request->subject_id;
        $form = (int) $request->form;
        $id= (int) $request->model;
        $setting = UserMedalSettings::find(['id' => $id])->first();

        if(empty($setting)) return response()->json([
            'status' => 'error'
        ]);

        if($form==1)
            $query = OnlineCourse::query()->select(['id', \DB::raw('CONCAT(code, \' - \', name) AS text')]);
        else $query = OfflineCourse::query()->select(['id', \DB::raw('CONCAT(code, \' - \', name) AS text')]);
    //    $query->where('status', '=', 1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%' . $search . '%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        if($request->start_date) {
            $start_date = date_convert($request->start_date, $request->start_hour.':'.$request->start_minute.':00');
        }
        else $start_date = date('Y-m-d H:i:s', $setting->start_date);

        if($request->end_date) {
            $end_date = date_convert($request->end_date, $request->end_hour.':'.$request->end_minute.':00');
        }
        else $end_date = date('Y-m-d H:i:s', $setting->end_date);

       $query->where('start_date', '>=', $start_date);
       $query->where('end_date', '<=', $end_date);

        if ($subject_id) {
            $query->where('subject_id', '=', $subject_id);
        }

        $results = $query->get();
        json_result($results);
    }

    public function loadQuiz(Request $request) {
        $search = $request->search;
        $id= (int) $request->id;
        $setting = UserMedalSettings::find(['id' => $id])->first();

        if(empty($setting)) return response()->json([
            'status' => 'error'
        ]);
        $query = Quiz::query()->select(['id', \DB::raw('CONCAT(code, \' - \', name) AS text')]);
        $query->where('is_open', '=', 1);
        $query->where('status', '=', 1);

       $query->whereIn('id', function ($subquery) use ($setting){
           $subquery->select(['quiz_id'])
               ->from('el_quiz_part')
              ->where('start_date', '>=', date('Y-m-d H:i:s',$setting->start_date))
              ->where('start_date', '<=', date('Y-m-d H:i:s',$setting->end_date));
        });

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('code', 'like', '%' . $search . '%');
                $sub_query->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('id', 'desc');
        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        json_result($data);
    }

    public function saveObject($settings_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
            'parent_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'nullable',
        ], $request);

        $title_id = explode(',', $request->input('title_id'));
        $unit_id = $request->input('unit_id');
        $parent_id = $request->input('parent_id');

        if ($parent_id && is_null($unit_id)){
            if (UserMedalObject::checkObjectUnit($settings_id, $parent_id)){

            }else{
                $model = new UserMedalObject();
                $model->settings_id = $settings_id;
                $model->unit_id = $parent_id;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }
        if ($unit_id) {
            foreach ($unit_id as $item){
                if (UserMedalObject::checkObjectUnit($settings_id, $item)){
                    continue;
                }
                $model = new UserMedalObject();
                $model->settings_id = $settings_id;
                $model->unit_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }else{
            foreach ($title_id as $item){
                if (UserMedalObject::checkObjectTitle($settings_id, $item)){
                    continue;
                }
                $model = new UserMedalObject();
                $model->settings_id = $settings_id;
                $model->title_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_title'),
            ]);
        }
    }

    public function getUserObject($settings_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = UserMedalObject::query();
        $query->select([
            'a.*',
            'b.code AS profile_code',
            'b.lastname',
            'b.firstname',
            'b.email',
            'b.title_name',
            'b.unit_name',
            'b.parent_unit_name AS parent_name'
        ]);
        $query->from('el_usermedal_object AS a');
        $query->leftJoin('el_profile_view AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.settings_id', '=', $settings_id);
        $query->where('a.title_id', '=', null);
        $query->where('a.unit_id', '=', null);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->profile_name = $row->lastname . ' ' . $row->firstname;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function getObject($settings_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = UserMedalObject::query();
        $query->select(['a.*', 'b.name AS title_name', 'c.name AS unit_name', 'd.name AS parent_name']);
        $query->from('el_usermedal_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('a.settings_id', '=', $settings_id);
        $query->where('a.user_id', '=', null);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            if (empty($row->parent_name)){
                $row->parent = $row->unit_name;
                $row->unit = '';
            }else{
                $row->parent = $row->parent_name;
                $row->unit = $row->unit_name;
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeObject($settings_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        UserMedalObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importObject($settings_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $type_import = $request->type_import;
        $import = new ProfileImport($settings_id, $type_import);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.usermedal-setting.edit', ['id' => $settings_id]),
        ]);
    }


    public function getChild($survey_id, Request $request){
        $unit_id = $request->id;
        $unit = Unit::find($unit_id);

        $childs = Unit::where('parent_code', '=', $unit->code)->get(['id', 'name', 'code']);

        $count_child = [];
        $page_child = [];
        foreach ($childs as $item){
            $count_child[$item->id] = Unit::countChild($item->code);
            $page_child[$item->id] = route('module.usermedal-setting.get_tree_child', ['id' => $survey_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }

    public function getTreeChild($survey_id, Request $request){
        $parent_code = $request->parent_code;
        return view('usermedal::backend.usermedal-settings.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

}
