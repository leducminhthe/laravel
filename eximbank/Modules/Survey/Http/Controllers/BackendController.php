<?php

namespace Modules\Survey\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Modules\Survey\Entities\SurveyAnswerMatrix;
use Modules\Survey\Entities\SurveyAnswerMatrix2;
use Modules\Survey\Entities\SurveyQuestion2;
use Modules\Survey\Entities\SurveyQuestionAnswer;
use Modules\Survey\Entities\SurveyQuestionAnswer2;
use Modules\Survey\Entities\SurveyQuestionCategory;
use Modules\Survey\Entities\SurveyQuestionCategory2;
use Modules\Survey\Entities\SurveyTemplate2;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Entities\SurveyObject;
use Modules\Survey\Entities\SurveyPopup;
use Modules\Survey\Entities\SurveyQuestion;
use Modules\Survey\Entities\SurveyTemplate;
use Modules\Survey\Entities\SurveyUser;
use Modules\Survey\Imports\ProfileImport;
use Modules\Survey\Entities\SurveyQuestionOnline;
use App\Models\UserRole;
use App\Models\Permission;

class BackendController extends Controller
{
    public function index()
    {
        return view('survey::backend.survey.index',[
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Survey::addGlobalScope(new DraftScope());
        $query = Survey::query();
        $query->select([
            'id',
            'name',
            'template_id',
            'status',
            'end_date',
            'start_date',
        ]);
        $query->from('el_survey');

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $template = SurveyTemplate::find($row->template_id);
            $survey_object_user = SurveyObject::whereSurveyId($row->id)->pluck('user_id')->toArray();
            $survey_object_title = SurveyObject::whereSurveyId($row->id)->pluck('title_id')->toArray();
            $survey_object_unit = SurveyObject::whereSurveyId($row->id)->pluck('unit_id')->toArray();

            if($template){
                if($template->type == 1) {
                    $row->count_ques = SurveyQuestionOnline::where('template_id', '=', @$template->id)->count();
                    $row->survey_online = 1;
                    if(isset($row->template_id)) {
                        $row->review = route('module.survey.template_online.review', ['survey_id' => $row->id, 'id' => $template->id]);
                    }
                } else {
                    $row->survey_online = 0;
                    $template_category = SurveyQuestionCategory::whereTemplateId(@$template->id)->pluck('id')->toArray();
                    $row->count_ques = SurveyQuestion::whereIn('category_id', $template_category)->count();
                    if(isset($row->template_id)) {
                        $row->review = route('module.survey.review_template', [$row->id]);
                    }
                }
            }

            $row->count_object = Profile::query()
                ->where('user_id', '>', 2)
                ->where(function($sub) use($survey_object_user, $survey_object_title, $survey_object_unit){
                    $sub->orWhereIn('user_id', $survey_object_user);
                    $sub->orWhereIn('title_id', $survey_object_title);
                    $sub->orWhereIn('unit_id', $survey_object_unit);
                })
                ->count();

            $row->count_survey_user = SurveyUser::where('survey_id', '=', $row->id)->where('send', '=', 1)->count();
            $row->report_url = route('module.survey.report.export', ['survey_id' => $row->id]);
            $row->report_detail_url = route('module.survey.report.index', ['survey_id' => $row->id]);
            $row->edit_url = route('module.survey.edit', ['id' => $row->id]);
            $qrcode = route('qrcode_process',['survey_id' => $row->id, 'type' => 'survey']);
            if($row->end_date > date('Y-m-d H:i:s') && $row->status == 1 && $row->start_date < date('Y-m-d H:i:s') && $row->type != 2) {
                $row->qrcode = \QrCode::size(300)->generate($qrcode);
            } else {
                $row->qrcode = '';
            }
            $row->start_date = get_date($row->start_date, 'H:i d/m/Y');
            $row->end_date = get_date($row->end_date, 'H:i d/m/Y');
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'start_date' => 'required_if:type,==,1',
            'end_date' => 'required_if:type,==,1',
            'start_hour' => 'required',
            'start_min' => 'required',
            'more_suggestions' => 'required_if:type,!=,2',
            'custom_template' => 'nullable',
            'image' => 'nullable|string',
        ], $request, Survey::getAttributeName());

        $model = Survey::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->created_by = profile()->user_id;
        $model->updated_by = profile()->user_id;
        $model->type = $request->type;

        if($request->type == 2) {
            $model->status = 0;
            $model->more_suggestions = 0;
        }

        if($request->type == 1) {
            $start_time = $request->input('start_hour') . ':' . $request->input('start_min') . ':00';
            $end_time = $request->input('end_hour') . ':' . $request->input('end_min') . ':00';

            $start_date = date_convert($request->input('start_date'), $start_time);
            $end_date = $request->input('end_date') ? date_convert($request->input('end_date'), $end_time) : null;

            $model->start_date = $start_date;
            $model->end_date = $end_date;

            if ($request->input('end_date')){
                if($model->start_date >= $model->end_date){
                    json_result([
                        'status' => 'error',
                        'message' => 'Thời gian kết thúc phải sau Thời gian bắt đầu',
                    ]);
                }
            }

            if (empty($request->id)){
                if($model->start_date < date('Y-m-d')){
                    json_result([
                        'status' => 'error',
                        'message' => 'Thời gian khảo sát tính từ ngày hiện tại',
                    ]);
                }
            }
        }

        if ($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image);
        }

        $model->custom_template  = '';

        if ($model->save()) {
            if($request->template == 1) {
                $template2 = SurveyTemplate2::whereSurveyId($model->id)->first();
                if(isset($template2)) {
                    json_result([
                        'redirect' => route('module.survey.template.edit', ['survey_id' => $model->id, 'id' => $template2->id]),
                    ]);
                } else {
                    json_result([
                        'redirect' => route('module.survey.template.create', ['survey_id' => $model->id]),
                    ]);
                }
            }

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.survey.edit', ['id' => $model->id]),
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function form($id = 0) {
        $errors = session()->get('errors');
        \Session::forget('errors');
        Titles::addGlobalScope(new DraftScope());
        $survey_templates = SurveyTemplate::where('course', '=', 0)->get(['id','name']);

        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $title = Titles::select(['id','name','code'])->get();

        if(!Permission::isAdmin()) {
            $userUnit = session()->get('user_unit');
            $user_role = UserRole::query()
                ->select(['c.unit_id', 'c.type', 'd.code', 'd.name'])->disableCache()
                ->from('el_user_role as a')
                ->join('el_role_has_permission_type as b', 'b.role_id', '=', 'a.role_id')
                ->join('el_permission_type_unit as c', 'c.permission_type_id', '=', 'b.permission_type_id')
                ->join('el_unit as d', 'd.id', '=', 'c.unit_id')
                ->where('a.user_id', '=', profile()->user_id)
                ->where('c.unit_id', '=', $userUnit)
                ->first();
            if($user_role->type == 'group-child') {
                $getArray = Unit::getArrayChild($user_role->code);
                array_push($getArray, $user_role->unit_id);
                $corporations = Unit::select(['id','name','code'])->where('level', '=', 1)->where('status', '=', 1)->whereIn('id', $getArray)->get();
            } else {
                $corporations = Unit::select(['id','name','code'])->where('level', '=', 1)->where('status', '=', 1)->where('id', $user_role->unit_id)->get();
            }
        } else {
            $corporations = Unit::select(['id','name','code'])->where('level', '=', 1)->where('status', '=', 1)->get();
        }

        if ($id) {
            $model = Survey::find($id);
            $page_title = $model->name;
            $surver_user = SurveyUser::whereSurveyId($model->id)->first();

            return view('survey::backend.survey.form', [
                'model' => $model,
                'page_title' => $page_title,
                'survey_templates' => $survey_templates,
                'max_unit' => $max_unit,
                'level_name' => $level_name,
                'title' => $title,
                'corporations' => $corporations,
                'surver_user' => $surver_user,
            ]);
        }

        $model =  new Survey();
        $page_title = trans('lasurvey.add_new') ;

        return view('survey::backend.survey.form', [
            'model' => $model,
            'page_title' =>$page_title,
            'survey_templates' => $survey_templates,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'title' => $title,
            'corporations' => $corporations,
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $survey_user = SurveyUser::query()->where('survey_id', '=', $id);
            if ($survey_user->exists()){
                json_message('Không thể xóa vì đã có Học viên làm khảo sát', 'error');
            }
            SurveyPopup::where('survey_id', $id)->delete();
            SurveyObject::whereSurveyId($id)->delete();
            Survey::find($id)->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveObject($survey_id, Request $request){
        $this->validateRequest([
            'unit_id' => 'nullable|exists:el_unit,id',
            'parent_id' => 'nullable|exists:el_unit,id',
            'title_id' => 'nullable',
        ], $request);

        $title_id = explode(',', $request->input('title_id'));
        $unit_id = $request->input('unit_id');
        $parent_id = $request->input('parent_id');

        if ($parent_id && is_null($unit_id)){
            if (SurveyObject::checkObjectUnit($survey_id, $parent_id)){

            }else{
                $model = new SurveyObject();
                $model->survey_id = $survey_id;
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
                if (SurveyObject::checkObjectUnit($survey_id, $item)){
                    continue;
                }
                $model = new SurveyObject();
                $model->survey_id = $survey_id;
                $model->unit_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_unit'),
            ]);
        }else{
            foreach ($title_id as $item){
                if (SurveyObject::checkObjectTitle($survey_id, $item)){
                    continue;
                }
                $model = new SurveyObject();
                $model->survey_id = $survey_id;
                $model->title_id = $item;
                $model->save();
            }
            json_result([
                'status' => 'success',
                'message' => trans('laother.success_add_title'),
            ]);
        }
    }

    public function getUserObject($survey_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SurveyObject::query();
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
        $query->from('el_survey_object AS a');
        $query->leftJoin('el_profile_view AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.survey_id', '=', $survey_id);
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

    public function getObject($survey_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SurveyObject::query();
        $query->select(['a.*', 'b.name AS title_name', 'c.name AS unit_name', 'd.name AS parent_name']);
        $query->from('el_survey_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code');
        $query->where('a.survey_id', '=', $survey_id);
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

    public function removeObject($survey_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        SurveyObject::destroy($item);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function getPopup($survey_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = SurveyPopup::query();
        $query->where('survey_id', '=', $survey_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->date = get_date($row->start_date, 'H:i d/m/Y') . ' <i class="fa fa-arrow-right"></i> ' . get_date($row->end_date, 'H:i d/m/Y');
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function savePopup($survey_id, Request $request){
        $this->validateRequest([
            'num_notify' => 'required',
            'start_date' => 'required',
            'start_hour' => 'required',
            'start_min' => 'required',
            'end_date' => 'required',
            'end_hour' => 'required',
            'end_min' => 'required',
        ], $request, [
            'num_notify' => 'Số lần hiện thông báo',
            'start_date' => trans('latraining.start_date'),
            'end_date' => trans('latraining.end_date'),
        ]);

        $survey = Survey::find($survey_id);
        $survey->num_notify = $request->num_notify;
        $survey->num_popup = $request->num_notify;
        $survey->save();

        $start_time = $request->input('start_hour') . ':' . $request->input('start_min') . ':00';
        $end_time = $request->input('end_hour') . ':' . $request->input('end_min') . ':00';

        $start_date = date_convert($request->input('start_date'), $start_time);
        $end_date = date_convert($request->input('end_date'), $end_time);

        if($start_date >= $end_date){
            json_result([
                'status' => 'error',
                'message' => 'Thời gian kết thúc phải sau Thời gian bắt đầu',
            ]);
        }
        if($start_date < $survey->start_date){
            json_result([
                'status' => 'error',
                'message' => 'Thời gian bắt đầu ngoài thời gian khảo sát',
            ]);
        }
        if($end_date > $survey->end_date){
            json_result([
                'status' => 'error',
                'message' => 'Thời gian bắt đầu ngoài thời gian khảo sát',
            ]);
        }

        $check1 = SurveyPopup::query();
        $check1->where('start_date', '<=', $start_date);
        $check1->where('end_date', '>=', $start_date);
        $check1->where('survey_id', '=', $survey_id);
        if ($check1->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian popup không họp lệ',
            ]);
        }

        $check2 = SurveyPopup::query();
        $check2->where('start_date', '<=', $end_date);
        $check2->where('end_date', '>=', $end_date);
        $check2->where('survey_id', '=', $survey_id);
        if ($check2->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Thời gian popup không họp lệ',
            ]);
        }


        $model = new SurveyPopup();
        $model->fillable($request->all());
        $model->survey_id = $survey_id;
        $model->start_date = $start_date;
        $model->end_date = $end_date;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => 'Thêm thành công',
        ]);
    }

    public function removePopup($survey_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        SurveyPopup::destroy($item);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function importObject($survey_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $type_import = $request->type_import;
        // kiểm tra nhân viên có thuộc đơn vị quản lý hay ko
        if(!Permission::isAdmin()){
            $userUnit = session()->get('user_unit');
            $user_role = UserRole::query()
            ->select(['c.unit_id', 'c.type', 'd.code', 'd.name'])->disableCache()
            ->from('el_user_role as a')
            ->join('el_role_has_permission_type as b', 'b.role_id', '=', 'a.role_id')
            ->join('el_permission_type_unit as c', 'c.permission_type_id', '=', 'b.permission_type_id')
            ->join('el_unit as d', 'd.id', '=', 'c.unit_id')
            ->where('a.user_id', '=', profile()->user_id)
            ->where('c.unit_id', '=', $userUnit)
            ->first();
        }

        $import = new ProfileImport($survey_id, $user_role, $type_import);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.survey.edit', ['id' => $survey_id]),
        ]);
    }

    public function ajaxIsopenPublish(Request $request){
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Thông báo',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach($ids as $id){
            $model = Survey::findOrFail($id);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save')
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
            $page_child[$item->id] = route('module.survey.get_tree_child', ['id' => $survey_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }

    public function getTreeChild($survey_id, Request $request){
        $parent_code = $request->parent_code;
        return view('survey::backend.survey.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    public function reviewTemplate($id){
        $item = Survey::findOrFail($id);
        $template = SurveyTemplate::find($item->template_id);

        return view('survey::modal.review_template', [
            'item' => $item,
            'template' => $template,
        ]);
    }

    public function copy(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => 'Năng lực',
        ]);
        $title_id = $request->title_id;
        $ids = $request->input('ids', null);
        foreach($ids as $id){
            $survey = Survey::find($id);
            $newName = $survey->name . '_copy'. rand(2, 50);
            $template2 = SurveyTemplate2::whereSurveyId($id)->first();

            $template = SurveyTemplate::find($template2->id);
            $newTemplate = $template->replicate();
            $newTemplate->name = $newName;
            $newTemplate->save();

            $newSurvey = $survey->replicate();
            $newSurvey->name = $newName;
            $newSurvey->template_id = $newTemplate->id;
            $newSurvey->save();

            $categories = SurveyQuestionCategory::where('template_id', '=', $template2->id)->get();
            foreach ($categories as $category){
                $newCategory = $category->replicate();
                $newCategory->template_id = $newTemplate->id;
                $newCategory->save();

                $questions = SurveyQuestion::where('category_id', '=', $category->id)->get();
                foreach ($questions as $question){
                    $newQuestion = $question->replicate();
                    $newQuestion->category_id = $newCategory->id;
                    $newQuestion->save();

                    $answers = SurveyQuestionAnswer::where('question_id', '=', $question->id)->get();
                    foreach ($answers as $answer){
                        $newAnswer = $answer->replicate();
                        $newAnswer->question_id = $newQuestion->id;
                        $newAnswer->save();
                    }
                }
            }

            $categories = SurveyQuestionCategory::query()->where('template_id', $newTemplate->id)->get()->toArray();

            $new_template = new SurveyTemplate2();
            $new_template->id = $newTemplate->id;
            $new_template->survey_id = $newSurvey->id;
            $new_template->name = $newName;
            $new_template->save();

            foreach ($categories as $category){
                $new_category = new SurveyQuestionCategory2();
                $new_category->fill($category);
                $new_category->id = $category['id'];
                $new_category->survey_id = $newSurvey->id;
                $new_category->save();

                $questions = SurveyQuestion::query()->where('category_id', $category['id'])->get()->toArray();
                foreach ($questions as $question){
                    $new_question = new SurveyQuestion2();
                    $new_question->fill($question);
                    $new_question->id = $question['id'];
                    $new_question->survey_id = $newSurvey->id;
                    $new_question->save();

                    $answers = SurveyQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                    foreach ($answers as $answer){
                        $new_answer = new SurveyQuestionAnswer2();
                        $new_answer->fill($answer);
                        $new_answer->id = $answer['id'];
                        $new_answer->survey_id = $newSurvey->id;
                        $new_answer->save();
                    }

                    $answers_matrix = SurveyAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                    foreach ($answers_matrix as $answer_matrix){
                        $new_answer_matrix = new SurveyAnswerMatrix2();
                        $new_answer_matrix->fill($answer_matrix);
                        $new_answer_matrix->survey_id = $newSurvey->id;
                        $new_answer_matrix->save();
                    }
                }
            }
        }

        json_result([
            'message' => 'Thêm thành công',
            'status' => 'success'
        ]);
    }
}
