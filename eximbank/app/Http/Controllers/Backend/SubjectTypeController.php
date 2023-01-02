<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Subject;
use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\SubjectType;
use App\Exports\TrainingProgramExport;
use App\Imports\TrainingProgramImport;
use Modules\Certificate\Entities\Certificate;
use App\Models\Categories\SubjectTypeSubject;
use App\Models\Categories\SubjectTypeObject;
use App\Models\Categories\SubjectTypeResult;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Imports\ProfileImport;
use Modules\Offline\Entities\OfflineResult;
use Illuminate\Support\Facades\DB;
use App\Models\Categories\SubjectTypeUser;

class SubjectTypeController extends Controller
{
    public function index() {
        \Session::forget('errors');
        return view('backend.category.subject_type.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        SubjectType::addGlobalScope(new DraftScope());
        $query = SubjectType::query();
        if ($search) {
            $query->orWhere('code', 'like', '%'. $search .'%');
            $query->orWhere('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.subject_type.edit', ['id' => $row->id]);
            $row->startdate = get_date($row->startdate);
            $row->enddate = get_date($row->enddate);

            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $model = SubjectType::select(['id','status','code','name','certificate_id','startdate','enddate'])->where('id', $request->id)->first();
        $subjects = Subject::active()->get();
        $certificate = Certificate::where("type",2)->get(['id','code']);

        $subjectsSelected = null;
        $checkIssetResult = false;
        if($model->id){
            $page_title = $model->name;
            $subjectsSelected = SubjectTypeSubject::where("subject_type_id",$model->id)->get()->pluck("subject_id")->toArray();

            $result = SubjectTypeResult::whereSubjectTypeId($model->id)->get();
            if(!$result->isEmpty()) {
                $checkIssetResult = true;
            }
        }else{
            $page_title = trans('labutton.add_new');
        }

        $corporations = Unit::select(['id','name','code'])->where('level', '=', 1)->where('status', '=', 1)->get();
        $title = Titles::select(['id','name','code'])->get();

        return view('backend.category.subject_type.form', [
            'model' => $model,
            'subjects' => $subjects,
            'certificate' => $certificate,
            'subjectsSelected' => $subjectsSelected,
            'page_title' => $page_title,
            'tabs' => $request->tabs,
            'corporations' => $corporations,
            'title' => $title,
            'checkIssetResult' => $checkIssetResult
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_subject_type,code,'. $request->id,
            'name' => 'required',
            'startdate' => 'required',
            'enddate' => 'required',
            'subjects' => 'required',
            'certificate_id' => 'required',
            'status' => 'required|in:0,1'
        ], $request, SubjectType::getAttributeName());

        $model = SubjectType::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $model->startdate = date_convert($request->input('startdate'));
        $model->enddate = date_convert($request->input('enddate'), '23:59:59');

        if (!$request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = profile()->user_id;
        if ($model->save()) {
            $subjects = $request->subjects;

            if(!empty($subjects)){
                SubjectTypeSubject::where("subject_type_id", $model->id)->delete();

                foreach ($subjects as $k => $v){
                    $subject_type_subject = new SubjectTypeSubject();
                    $subject_type_subject->subject_type_id = $model->id;
                    $subject_type_subject->subject_id = $v;
                    $subject_type_subject->save();
                }
            }

            if($request->updated_change == 1) {
                $this->updateResult($model);
            }

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.category.subject_type.edit', ['id' => $model->id])
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        SubjectType::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = SubjectType::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = SubjectType::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function saveObject($subject_type_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'required_if:object,1|exists:el_unit,id',
            'parent_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'required_if:object,2',
        ], $request, [
            'unit_id' => trans('lamenu.unit'),
            'title_id' => trans('la.title'),
        ]);

        $title_id = explode(',', $request->input('title_id'));
        $unit_id = $request->input('unit_id');
        $parent_id = $request->input('parent_id');

        if ($parent_id && is_null($unit_id)){
            if (!SubjectTypeObject::checkObjectUnit($subject_type_id, $parent_id)){
                $model = new SubjectTypeObject();
                $model->subject_type_id = $subject_type_id;
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
                if (!SubjectTypeObject::checkObjectUnit($subject_type_id, $item)){
                    $model = new SubjectTypeObject();
                    $model->subject_type_id = $subject_type_id;
                    $model->unit_id = $item;
                    $model->save();
                }
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }else{
            foreach ($title_id as $item){
                if (!SubjectTypeObject::checkObjectTitle($subject_type_id, $item)){
                    $model = new SubjectTypeObject();
                    $model->subject_type_id = $subject_type_id;
                    $model->title_id = $item;
                    $model->save();
                }
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_title'),
            ]);
        }
    }

    public function getUserObject($subject_type_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SubjectTypeObject::query();
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
        $query->from('el_subject_type_object AS a');
        $query->leftJoin('el_profile_view AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.subject_type_id', '=', $subject_type_id);
        $query->where('a.title_id', '=', null);
        $query->where('a.unit_id', '=', null);
        $query->where('b.user_id', '>', 2);

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

    public function getObject($subject_type_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SubjectTypeObject::query();
        $query->select(['a.*', 'b.name AS title_name', 'c.name AS unit_name', 'd.name AS parent_name']);
        $query->from('el_subject_type_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('a.subject_type_id', '=', $subject_type_id);
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

    public function removeObject($subject_type_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        SubjectTypeObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importObject($subject_type_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $type_import = $request->type_import;
        $import = new ProfileImport($subject_type_id, $type_import);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('backend.category.subject_type.edit', ['id' => $subject_type_id]) . '&tabs=object',
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
            $page_child[$item->id] = route('module.subject-type.get_tree_child', ['id' => $survey_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }

    public function getTreeChild($survey_id, Request $request){
        $parent_code = $request->parent_code;
        return view('backend.category.subject-types.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    public function pushDataObject(Request $request){
        SubjectTypeResult::where("subject_type_id",$request->id)->delete();
        $obj = SubjectTypeObject::where("subject_type_id",$request->id)->get();
        $arrUser = array();
        foreach ($obj as $k => $v){
            if($v->title_id){
                $users = Profile::where("title_id",$v->title_id)->get(['user_id']);
                foreach ($users as $u){
                    $arrUser[] = $u->user_id;
                }
            }
            else  if($v->unit_id){
                $users = Profile::where("unit_id",$v->unit_id)->get(['user_id']);
                foreach ($users as $u){
                    $arrUser[] = $u->user_id;
                }
            }
            else  if($v->user_id){
                $arrUser[] = $v->user_id;
            }

        }

        $arrUser = array_unique($arrUser);
        $values  = array();
        foreach ($arrUser as $v) {
            $values[] = ['user_id' => $v, 'subject_type_id' =>$request->id, 'course_finished_total' =>0];
        }

        \DB::table('el_subject_type_result')->insert(
            $values
        );

       return redirect()->back();

    }

    public function getUserResult(Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $subject_type_id = $request->subject_type_id;

        $subject_list = SubjectTypeSubject::whereSubjectTypeId($subject_type_id)->count();
        $object_title = SubjectTypeObject::whereSubjectTypeId($subject_type_id)->pluck('title_id')->toArray();
        $object_unit = SubjectTypeObject::whereSubjectTypeId($subject_type_id)->pluck('unit_id')->toArray();
        $object_user = SubjectTypeObject::whereSubjectTypeId($subject_type_id)->pluck('user_id')->toArray();

        $query = Profile::query();
        $query->select([
            'id',
            'user_id',
            'code AS profile_code',
            'lastname',
            'firstname',
        ]);
        $query->where('user_id', '>', 2);
        $query->where(function($sub) use($object_title, $object_unit, $object_user) {
            $sub->orWhereIn('title_id', $object_title);
            $sub->orWhereIn('unit_id', $object_unit);
            $sub->orWhereIn('user_id', $object_user);
        });

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $result = SubjectTypeResult::whereSubjectTypeId($subject_type_id)->where('user_id', $row->user_id)->first();

            $row->profile_name = $row->lastname .' '. $row->firstname;
            $row->finished_total = ($result ? $result->course_finished_total : 0) .'/'. $subject_list;
            $row->percent = ($result ? round(($result->course_finished_total/$subject_list)*100, 2) : 0) .'%';
            $row->date_complete = $result->updated_at ? get_date($result->updated_at, 'H:i:s d/m/Y') : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    // CẬP NHẬT KẾT QUẢ CHƯƠNG TRÌNH ĐÀO TẠO
    public function updateResult($model) {
        $subject_list = SubjectTypeSubject::whereSubjectTypeId($model->id)->pluck('subject_id')->toArray();
        $object_title = SubjectTypeObject::whereSubjectTypeId($model->id)->pluck('title_id')->toArray();
        $object_unit = SubjectTypeObject::whereSubjectTypeId($model->id)->pluck('unit_id')->toArray();
        $object_user = SubjectTypeObject::whereSubjectTypeId($model->id)->pluck('user_id')->toArray();

        SubjectTypeUser::where('subject_type_id', $model->id)->delete();
        SubjectTypeResult::where('subject_type_id', $model->id)->delete();

        $queryProfile = Profile::query();
        $queryProfile->where('user_id', '>', 2);
        $queryProfile->where(function($sub) use($object_title, $object_unit, $object_user) {
            $sub->orWhereIn('title_id', $object_title);
            $sub->orWhereIn('unit_id', $object_unit);
            $sub->orWhereIn('user_id', $object_user);
        });
        $profiles = $queryProfile->pluck('user_id')->toArray();

        $queryOfflineResult = DB::query();
        $queryOfflineResult->select([
            'result.user_id',
            'result.created_at',
            'result.result',
            'course.subject_id',
        ]);

        $queryOfflineResult->from('el_offline_result AS result')
            ->join('el_offline_course AS course', 'course.id', '=', 'result.course_id')
            ->whereIn('result.user_id', $profiles)
            ->whereIn('course.subject_id', $subject_list);
        $offlineResults = $queryOfflineResult->get();
        foreach ($offlineResults as $offlineResult) {
            $this->updateCertificateTraining($model, $offlineResult->subject_id, $offlineResult->user_id, $offlineResult->result, $offlineResult->created_at);
        }

        $queryOnlineResult = DB::query();
        $queryOnlineResult->select([
            'result.user_id',
            'result.created_at',
            'result.result',
            'course.subject_id',
        ]);

        $queryOnlineResult->from('el_online_result AS result')
            ->join('el_online_course AS course', 'course.id', '=', 'result.course_id')
            ->whereIn('result.user_id', $profiles)
            ->whereIn('course.subject_id', $subject_list);
        $onlineResults = $queryOnlineResult->get();
        foreach ($onlineResults as $onlineResult) {
            $this->updateCertificateTraining($model, $onlineResult->subject_id, $onlineResult->user_id, $onlineResult->result, $onlineResult->created_at);
        }
    }

    private function updateCertificateTraining($modelSubjectType, $subject_id, $user_id, $result, $time_complete) {
        if($result == 1){
            //Nếu thời gian hoàn thành trong thời gian thiết lập và user nằm trong đối tượng cấp chứng chỉ
            if($modelSubjectType->startdate <= $time_complete && $time_complete <= $modelSubjectType->enddate){
                SubjectTypeUser::updateOrCreate([
                    'subject_type_id' => $modelSubjectType->id,
                    'subject_id' => $subject_id,
                    'user_id' => $user_id,
                ]);

                $course_finished_total  = SubjectTypeUser::where('subject_type_id', $modelSubjectType->id)->where('user_id', $user_id)->count();

                SubjectTypeResult::updateOrCreate([
                    'subject_type_id' => $modelSubjectType->id,
                    'user_id' => $user_id
                ],[
                    'course_finished_total' => $course_finished_total
                ]);
            }
        }else{
            $check = SubjectTypeResult::where('subject_type_id', $modelSubjectType->id)->where('user_id', $user_id);
            if(!$check->exists()){
                SubjectTypeResult::create([
                    'subject_type_id' => $modelSubjectType->id,
                    'user_id' => $user_id,
                    'course_finished_total' => 0
                ]);
            }
        }
    }
}
