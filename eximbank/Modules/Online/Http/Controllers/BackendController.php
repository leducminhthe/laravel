<?php

namespace Modules\Online\Http\Controllers;

use App\Models\Automail;
use App\Models\Config;
use App\Models\RattingCourse;
use App\Models\CourseStatistic;
use App\Models\Categories\Area;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\UnitManager;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Profile;
use App\Scopes\DraftScope;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Modules\Certificate\Entities\Certificate;
use Modules\Online\Entities\ActivityScormAttempt;
use Modules\Online\Entities\ActivityScormScore;
use Modules\Online\Entities\MoodleCourse;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseActivityZoom;
use Modules\Online\Entities\OnlineCourseCondition;
use Modules\Online\Entities\OnlineCourseSettingPercent;
use Modules\Online\Entities\OnlineCourseView;
use Modules\Online\Entities\OnlineHistoryEdit;
use Modules\Online\Entities\OnlineInviteRegister;
use Modules\Online\Entities\OnlineObject;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineCourseDocument;
use Modules\Online\Entities\OnlineCourseUpload;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\PlanApp\Entities\PlanAppTemplate;
use Modules\Promotion\Entities\PromotionCourseSetting;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Rating\Entities\RatingTemplate;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\TrainingType;
use App\Models\Categories\TrainingObject;
use Modules\ReportNew\Entities\ReportNewExportBC05;
use Modules\ReportNew\Entities\ReportNewExportBC26;
use Modules\TrainingPlan\Entities\TrainingPlan;
use Modules\VirtualClassroom\Entities\VirtualClassroom;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Facades\Module;
use Modules\Offline\Entities\OfflineCourse;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Modules\Online\Entities\OnlineCourseNote;
use Modules\Online\Entities\OnlineComment;
use Modules\Online\Entities\OnlineRating;
use App\Exports\EvaluateExport;
use App\Models\Categories\TitleRank;
use Modules\Online\Entities\OnlineCourseAskAnswer;
use Modules\Online\Entities\OnlineCourseLesson;
use Modules\Online\Entities\OnlineCourseActivityFile;
use Modules\Online\Entities\OnlineCourseActivityVideo;
use Modules\Online\Entities\OnlineCourseActivityUrl;
use Modules\Online\Entities\OnlineCourseActivityQuiz;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use App\Models\Categories\TrainingForm;
use App\Models\ProfileView;
use Modules\Online\Entities\OnlineRegisterView;
use Modules\UserPoint\Entities\UserPointItem;
use Modules\UserPoint\Entities\UserPointSettings;
use App\Models\InteractionHistory;
use Modules\Online\Entities\SettingJoinOnlineCourse;
use Modules\Online\Entities\OnlineSurveyUser;
use Modules\Online\Entities\OnlineCourseActivitySurvey;
use App\Models\CourseTabEdit;
use Modules\Online\Exports\ExportActivitySurvey;
use Modules\Online\Imports\RegisterMultiCourseImport;
use Modules\Survey\Entities\Survey;
use App\Models\SubjectPrerequisiteCourse;
use App\Models\SubjectPrerequisite;
use App\Models\CourseView;
use Modules\Online\Imports\SettingJoinImport;
use Modules\PermissionApproved\Entities\PermissionApproved;

class BackendController extends Controller
{
    public $is_unit = 0;

    public function index() {
        $errors = session()->get('errors');
        \Session::forget('errors');

        return view('backend.training.index',[
        ]);
    }

    public function getData(Request $request)
    {
        $user_unit = session()->get('user_unit');
        $parent_unit = getParentUnit($user_unit);
        $count_level_permission_approve = PermissionApproved::where(['unit_id' => $parent_unit, 'model_approved' => 'el_online_course'])->count();

        $search = $request->input('search');
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        $subject_id = $request->input('subject_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_invited = null;
        $check_user_invited = OnlineInviteRegister::query()->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }

        $prefix= \DB::getTablePrefix();
        OnlineCourse::addGlobalScope(new DraftScope(null, null, $user_invited));
        $query = OnlineCourse::query();
        $query->select([
            'el_online_course.id',
            'el_online_course.code',
            'el_online_course.name',
            'el_online_course.isopen',
            'el_online_course.in_plan',
            'el_online_course.start_date',
            'el_online_course.end_date',
            'el_online_course.status',
            'el_online_course.register_deadline',
            'el_online_course.lock_course',
            'el_online_course.created_at',
            'el_online_course.approved_step',
            'el_online_course.created_by',
            'el_online_course.updated_by',
            'el_online_course.convert_course_plan',
            'c.name as subject_name',
        ]);
       $query->leftJoin('el_subject AS c', 'c.id', '=', 'el_online_course.subject_id');
       $query->where('el_online_course.offline', 0);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_online_course.name', 'like', '%' . $search . '%');
                $subquery->orWhere('el_online_course.code', 'like', '%' . $search . '%');
            });
        }

        if ($training_program_id) {
            $query->where('el_online_course.training_program_id', '=', $training_program_id);
        }
        if ($level_subject_id){
            $query->where('el_online_course.level_subject_id', '=', $level_subject_id);
        }
        if ($subject_id) {
            $query->where('el_online_course.subject_id', '=', $subject_id);
        }

        if ($start_date) {
            $query->where('el_online_course.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('el_online_course.start_date', '<=', date_convert($end_date, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.online.edit', ['id' => $row->id]);
            $row->register_url = route('module.online.register', ['id' => $row->id]);
            $row->register_secondary_url = route('module.online.register_secondary', ['id' => $row->id]);
            $row->register_deadline = get_date($row->register_deadline);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');

            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);

            $row->info_url = route('module.online.modal_info', ['course_id' => $row->id]);
            $row->count_level_permission_approve = $count_level_permission_approve;
            $row->parent_unit = $parent_unit;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null)
    {
		RatingTemplate::addGlobalScope(new DraftScope());

        $errors = session()->get('errors');
        \Session::forget('errors');

        $title_rank = TitleRank::select(['id','name','code'])->where('status', '=', 1)->get();
        $titles = Titles::select(['id','name','code'])->where('status', '=', 1)->get();
        $user_invited = false;

        $training_objects = TrainingObject::where('status',1)->get(['id','name']);
        $training_costs = TrainingCost::orderBy('type')->get(['id','name','type']);
        $course_cost = OnlineCourseCost::where('course_id', '=', $id)->get(['actual_amount','notes','plan_amount']);
        $total_actual_amount = OnlineCourseCost::getTotalActualAmount($id);
        $total_plan_amount = OnlineCourseCost::getTotalPlanAmount($id);
        // $teachers = TrainingTeacher::get();
        $templates = RatingTemplate::where('teaching_organization', 0)->get(['id','name']);
        $plan_app_template = PlanAppTemplate::get();
        $training_plan = TrainingPlan::get(['id','name']);
        $certificate = Certificate::where('type', 1)->get(['id','code']);
        $qrcode_survey_after_course = null;
        $training_forms = TrainingForm::where('training_type_id',1)->get(['id','name']);
        $units = Unit::select(['id','name','code'])->where('status', '=', 1)->get();

        $survey_register = Survey::query()
        ->select(['survey.id','survey.name'])
        ->from('el_survey as survey')
        ->join('el_survey_template2 as template', 'template.survey_id', '=', 'survey.id')
        ->where('survey.type', 3)
        ->where('survey.status', 1)
        ->get();

        $corporations = Unit::select(['id','name','code'])->where('level', '=', 1)->where('status', '=', 1)->get();
        if ($id) {
            $courseTabEdit = CourseTabEdit::where('course_id', $id)->where('course_type', 1)->pluck('tab_edit')->toArray();
            $ratting_course = RattingCourse::where('course_id',$id)->where('type',1)->first();
            $model = OnlineCourse::find($id);
            if (!$model) abort(404);
            $page_title = $model->name;
            $subject = Subject::where('id', $model->subject_id)->first();
            $training_program = TrainingProgram::where('id', $model->training_program_id)->first();
            $level_subject = LevelSubject::find($model->level_subject_id);

            $permission_save = userCan(['online-course-create', 'online-course-edit']);

            $lesson = OnlineCourseLesson::where('course_id', $id)->get();
            foreach ($lesson as $key => $item) {
                $item->activities = OnlineCourseActivity::getActivitiesByCourseLesson($item->id, $id);
            }
            $zoomLink= function ($id){
                $zoomActivity = OnlineCourseActivityZoom::findOrFail($id);
                return $zoomActivity->start_url;
            };
            $activities = OnlineCourseActivity::getByCourse($id);
            $condition = OnlineCourseCondition::getByCourse($id);

            $unit = explode(',', $model->unit_id);

            $count_activities_quiz = OnlineCourseActivity::where('course_id', '=', $id)
                ->where('activity_id', '=', 2)->count();

            $course_time = preg_replace("/[^0-9]/", '', $model->course_time);
            $course_time_unit = preg_replace("/[^a-z]/", '', $model->course_time);

            $qrcode_survey_after_course = json_encode(['course'=>$id,'course_type'=>1,'survey'=>$model->template_id,'type'=>'survey_after_course']);

            !empty($model->training_object_id) ? $get_training_object_id = json_decode($model->training_object_id) : $get_training_object_id = [];

            $check_user_invited = OnlineInviteRegister::query()
                ->where('course_id', '=', $id)
                ->where('user_id', '=', profile()->user_id);
            if ($check_user_invited->exists()){
                $user_invited = true;
            }

            $userpointOthers = UserPointItem::where("type","=",2)->where('ikey', '!=', 'online_complete')->get();

            $settings = UserPointSettings::where("item_id", "=", $id)->where("item_type", "=", 2)->get();
            $settingComplete = array();
            $settingModules = array();
            $settingOthers = array();

            if($settings->count() > 0){
                foreach ($settings as $k => $v){
                    if($v->pkey == 'online_complete'){
                        $settingComplete[] = $v;
                    }else if($v->pkey == 'online_activity_complete'){
                        $acties = OnlineCourseActivity::find($v->ref);
                        $v->refname = $acties->name;
                        $settingModules[] = $v;
                    }else {
                        $settingOthers[$v->pkey] = $v;
                    }
                }
            }else{
                $user_point_online_complete = UserPointItem::where("type","=",2)->where('ikey', '=', 'online_complete')->first();

                $start_date= strtotime($model->start_date);
                $end_date= strtotime($model->end_date);

                $complete = new UserPointSettings();
                $complete->pkey = 'online_complete';
                $complete->item_id = $id;
                $complete->item_type = 2;
                $complete->pvalue = $user_point_online_complete->default_value;
                $complete->start_date = $start_date;
                $complete->end_date = $end_date;
                $complete->save();

                $settingComplete[] = $complete;

                foreach ($userpointOthers as $k => $item){
                    $complete = new UserPointSettings();
                    $complete->pkey = $item->ikey;
                    $complete->item_id = $id;
                    $complete->item_type = 2;
                    $complete->pvalue = $item->default_value;
                    $complete->save();

                    $settingOthers[$item->ikey] = $complete;
                }
            }

            //thiết lập khóa học hoàn thành trước
            $course_others = OnlineCourse::where('id', '!=', $id)->where('status', 1)->get();
            $online_result = OnlineResult::whereCourseId($id)->exists();

            // ĐIỀU KIỆN TIÊN QUYẾT
            $subject_prerequisite = '';
            $title_prerequisite = '';
            $prerequisite_course = SubjectPrerequisiteCourse::where(['course_id' => $id, 'course_type' => 1])->first();

            if(!isset($prerequisite_course)) {
                $prerequisite_course = SubjectPrerequisite::where('subject_id', $model->subject_id)->first();
            }
            $subject_prerequisite = Subject::where('id', @$prerequisite_course->subject_prerequisite)->first(['id','name']);
            $title_prerequisite = Titles::where('id', @$prerequisite_course->title_id)->first(['id','name']);
            $register_quiz = Quiz::where(['course_id' => $id, 'course_type' => 1, 'quiz_type_by_offline' => 'register_quiz_id'])->first(['id', 'name']);

            return view('online::backend.online.form', [
                'title_rank' => $title_rank,
                'titles' => $titles,
                'model' => $model,
                'page_title' => $page_title,
                'subject' => $subject,
                'training_program' => $training_program,
                'training_costs' => $training_costs,
                'course_cost' => $course_cost,
                'total_actual_amount' => $total_actual_amount,
                'total_plan_amount' => $total_plan_amount,
                // 'teachers' => $teachers,
                'templates' => $templates,
                'training_plan' => $training_plan,
                'is_unit' => $model->unit_id,
                'unit' => $unit,
                'permission_save' => $permission_save,
                'activities' => $activities,
                'condition' => $condition,
                'course_time' => $course_time,
                'course_time_unit' => $course_time_unit,
                'count_activities_quiz' => $count_activities_quiz,
                'certificate' => $certificate,
                'qrcode_survey_after_course' => $qrcode_survey_after_course,
                'units' => $units,
                'level_subject' => $level_subject,
                'corporations' => $corporations,
                'get_training_object_id' => $get_training_object_id,
                'user_invited' => $user_invited,
                'training_forms' => $training_forms,
                'training_objects' => $training_objects,
                'ratting_course' => $ratting_course,
                'end_date_course' => date('Y-m-d', $model->enddate),
                'start_date_course' => date('Y-m-d', $model->startdate),
                'setting_complete' => $settingComplete,
                'setting_modules' => $settingModules,
                'setting_others' => $settingOthers,
                'userpoint_others' => $userpointOthers,
                'lesson' => $lesson,
                'plan_app_template' => $plan_app_template,
                'course_others' => $course_others,
                'zoomLink'=>$zoomLink,
                'courseTabEdit' => $courseTabEdit,
                'online_result' => $online_result,
                'survey_register' => $survey_register,
                'prerequisite_course' => $prerequisite_course,
                'subject_prerequisite' => $subject_prerequisite,
                'title_prerequisite' => $title_prerequisite,
                'register_quiz' => $register_quiz
            ]);
        }

        $model = new OnlineCourse();
        $page_title = trans('labutton.add_new') ;
        $permission_save = userCan(['online-course-create', 'online-course-edit']);

        return view('online::backend.online.form', [
            'title_rank' => $title_rank,
            'titles' => $titles,
            'model' => $model,
            'page_title' => $page_title,
            // 'teachers' => $teachers,
            'templates' => $templates,
            'training_plan' => $training_plan,
            'is_unit' => $this->is_unit,
            'permission_save' => $permission_save,
            'course_time' => null,
            'course_time_unit' => null,
            'certificate' => $certificate,
            'qrcode_survey_after_course' => $qrcode_survey_after_course,
            'units' => $units,
            'corporations' => $corporations,
            'user_invited' => $user_invited,
            'training_forms' => $training_forms,
            'training_objects' => $training_objects,
            'plan_app_template' => $plan_app_template,
            'survey_register' => $survey_register,
        ]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'code' => 'required|unique:el_online_course,code,' . $request->id,
            'name' => 'required',
            'in_plan' => 'nullable|exists:el_training_plan,id',
            'course_time' => 'nullable',
            'image' => 'nullable|string',
            'document' => 'nullable|string',
            'num_lesson' => 'nullable',
            // 'action_plan' => 'required|in:0,1',
            'start_date' => 'required|date_format:d/m/Y',
            // 'plan_app_template' => 'required_if:action_plan,1|nullable|integer',
            // 'plan_app_day' => 'required_if:action_plan,1|nullable|integer|max:1000',
        ], $request, OnlineCourse::getAttributeName());
        $course_time_unit = $request->course_time_unit;
        $unit_id = $request->unit_id;

        $subject = Subject::find($request->subject_id);

        $model = OnlineCourse::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->has_cert = $request->has_cert;
        $model->start_date = date_convert($request->input('start_date'));
        $model->end_date = $request->input('end_date') ? date_convert($request->input('end_date'), '23:59:59') : null;
        $model->register_deadline = $request->input('register_deadline') ? date_convert($request->input('register_deadline'), '23:59:59') : null;
        if($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image, 'online');
        }
        $model->document = path_upload($model->document);
        $model->unit_id = is_array($unit_id) ? implode(',', $unit_id) : null;
        $model->course_time = $request->input('course_time');
        $model->course_time_unit = $course_time_unit;
        $model->level_subject_id = @$subject->level_subject_id;
        // $model->rating_end_date = $request->input('rating_end_date') ? date_convert($request->input('rating_end_date'), '23:59:59') : null;

        $model->training_object_id = is_array($request->training_object_id) ? json_encode($request->training_object_id) : '';

        if ($model->end_date) {
            if ($model->start_date > $model->end_date) {
                json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
            }

            if ($model->register_deadline) {
                if ($model->register_deadline >= $model->end_date) {
                    json_message('Hạn đăng ký phải trước Ngày kết thúc', 'error');
                }
            }
        }

        if (empty($request->id)) {
            if ($model->start_date < date('Y-m-d')) {
                json_message('Ngày bắt đầu tính từ hiện tại', 'error');
            }
        }

        $date_original = null;
        if (empty($model->id)) {
            $model->created_by = profile()->user_id;
            $model->status = 2;
        }else{
            $date_original = OnlineCourse::where(['id' => $request->id])->value('start_date');
        }

        $model->updated_by = profile()->user_id;

        $save = $model->save();
        if ($save) {
            /********update thống kê khóa học **********/
            if (empty($request->id))
                CourseStatistic::update_course_insert_statistic($model->id,1);
            else
                CourseStatistic::update_course_update_statistic($model->id,1,$date_original);
            /*********************end***********************/

            /*************Update BC 26*******************/
            if ($request->in_plan){
                ReportNewExportBC26::query()
                ->updateOrCreate([
                    'training_plan_id' => $request->in_plan,
                    'subject_id' => $request->subject_id,
                    'year' => date('Y')
                ],[
                    'training_plan_id' => $request->in_plan,
                    'subject_id' => $request->subject_id,
                    'year' => date('Y'),
                    'course_action_'.$request->course_action => ($request->course_action ? 1 : 0)
                ]);
            }
            /*******************************************/

            $course_edit = CourseTabEdit::firstOrNew(['course_id' => $model->id, 'course_type' => 1, 'tab_edit' => 'edit']);
            $course_edit->course_id = $model->id;
            $course_edit->tab_edit = 'edit';
            $course_edit->course_type = 1;
            $course_edit->save();

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.online.edit', ['id' => $model->id])
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function saveTutorial(Request $request) {
        $this->validateRequest([
            'type_tutorial' => 'required',
            'content_tutorial' => "required_if:type_tutorial,1",
            'files_tutorial' => "required_if:type_tutorial,2|array|min:1",
            'files_tutorial.*' => 'required_if:type_tutorial,2|mimes:docx,xlsx,pdf|max:4096',
        ], $request, OnlineCourse::getAttributeName());
        $type_tutorial = $request->type_tutorial;
        $flag = $request->flag;
        if ($type_tutorial == 2 && $flag == 0) {
            if ($request->hasfile('files_tutorial')) {
                foreach ($request->file('files_tutorial') as $file) {
                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) . '-' . time() . '-' . Str::random(10) . '.' . $extension;
                    $storage = \Storage::disk('upload');
                    $new_paths[] = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);
                }
                $content = json_encode($new_paths);
            } else {
                return back()->with('false', 'Chưa chọn file');
            }
        } else if ($type_tutorial == 1) {
            $content = $request->content_tutorial;
        } else {
            $content = $request->content_of_id;
        }

        $model = OnlineCourse::find($request->id);
        $model->type_tutorial = $request->type_tutorial;
        $model->tutorial = $content;
        if ($model->save()) {
            $course_edit = CourseTabEdit::firstOrNew(['course_id' => $model->id, 'course_type' => 1, 'tab_edit' => 'tutorial']);
            $course_edit->course_id = $model->id;
            $course_edit->tab_edit = 'tutorial';
            $course_edit->course_type = 1;
            $course_edit->save();

            OnlineCourseView::where('id',$request->id)->update(['type_tutorial'=>$request->type_tutorial,'tutorial'=>$content]);
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);
        foreach ($ids as $id) {
            $course = OnlineCourse::find($id);
            $result = OnlineResult::where('course_id', '=', $id);
            if ($result->exists() || $course->status == 1){
                continue;
            }

            ReportNewExportBC05::query()
                ->where('course_id', '=', $id)
                ->where('course_type', '=', 1)
                ->delete();

            Quiz::where('course_id', '=', $id)
                ->where('course_type', '=', 1)
                ->update(['course_id' => 0, 'course_type' => 0]);

            VirtualClassroom::where('course_id', '=', $id)
                ->update(['course_id' => 0]);

            CourseStatistic::update_course_delete_statistic(1,$course->start_date);

            if ($course->delete()){
                $onlineCourse = OnlineCourse::find($id);
                $data = OnlineRegister::select('id','user_id','course_id')->with('user:user_id,code,firstname,lastname,gender,email')->where(['course_id'=>$id,'status'=>1])->get();
                foreach ($data as $item) {
                    $signature = getMailSignature($item->user_id);
                    $params = [
                        'signature' => $signature,
                        'gender' => $item->user->gender=='1'?'Anh':'Chị',
                        'full_name' => $item->user->full_name,
                        'firstname' => $item->user->firstname,
                        'course_code' => $onlineCourse->code,
                        'course_name' => $onlineCourse->name
                    ];
                    $user_id = [$item->user_id];
                    $automail = new Automail();
                    $automail->template_code = 'delete_course';
                    $automail->params = $params;
                    $automail->users = $user_id;
                    $automail->check_exists = true;
                    $automail->check_exists_status = 0;
                    $automail->object_id = $item->id;
                    $automail->object_type = 'delete_course_online';
                    $automail->addToAutomail();
                }
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function ajaxGetSubject(Request $request)
    {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
        ], $request, [
            'training_program_id' => trans('latraining.training_program'),
        ]);

        $training_program_id = $request->input('training_program_id');
        $subjects = Subject::where('training_program_id', '=', $training_program_id)->where('subsection', 0)->get();
        json_result($subjects);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);
        $user_invited = null;
        $check_user_invited = OnlineInviteRegister::query()->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                if ($user_invited && in_array($id, $user_invited)){
                    continue;
                }
                OnlineCourse::where('id', $id)->update(['isopen' => $status]);
                OnlineCourseView::where('id', $id)->update(['isopen' => $status]);
                CourseView::where(['course_id' => $id, 'course_type' => 1])->update(['isopen' => $status]);
            }
        } else {
            OnlineCourse::where('id', $ids)->update(['isopen' => $status]);
            OnlineCourseView::where('id', $ids)->update(['isopen' => $status]);
            CourseView::where(['course_id' => $ids, 'course_type' => 1])->update(['isopen' => $status]);
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function saveObject($course_id, Request $request)
    {
        // $this->validateRequest([
        //     'type' => 'required|in:1,2',
        // ], $request, [
        //     'title' => trans('latraining.title'),
        //     'unit' => 'Đơn vị',
        //     'type' => 'Loại đối tượng',
        // ]);

        $check_all_title = $request->check_all_title;
        $object_type = $request->object_type;
        $title_level = $request->title_rank_id;
        $titles = $request->title ? explode(',', $request->title) : [];
        $units = explode(',', $request->unit_id);
        $areas = $request->area4;
        $type = $request->type;

        if (empty($request->unit_id) && !empty($titles)) {
            json_message('Chưa chọn đơn vị', 'error');
        }

        if ($units && $titles){
            if (count($units) > 1){
                json_message('Khi chọn chức danh. Chỉ được chọn 1 đơn vị', 'error');
            }else{
                $unit = Unit::find($units[0]);
                if($check_all_title == 1) {
                    $all_title = Titles::where('status', 1)->whereNotIn('id', $titles)->pluck('id')->toArray();
                } else {
                    $all_title = $titles;
                }
                foreach ($all_title as $item) {
                    if (!Titles::where('id', '=', $item)->exists()) {
                        continue;
                    }

                    if (!Unit::where('id', '=', $unit->id)->exists()) {
                        continue;
                    }

                    if (OnlineObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->whereNull('title_id')->exists()) {
                        continue;
                    }

                    if (OnlineObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->where('title_id', '=', $item)->exists()) {
                        continue;
                    }

                    if (OnlineObject::where('course_id', '=', $course_id)->whereNull('unit_id')->where('title_id', '=', $item)->exists()) {
                        OnlineObject::where('course_id', '=', $course_id)
                            ->whereNull('unit_id')
                            ->where('title_id', '=', $item)
                            ->update([
                                'unit_id' => $unit->id,
                                'unit_level' => $unit->level,
                            ]);
                    }else{
                        $model = new OnlineObject();
                        $model->title_id = $item;
                        $model->unit_id = $unit->id;
                        $model->unit_level = $unit->level;
                        $model->type = $type;
                        $model->course_id = $course_id;
                        $model->created_by = profile()->user_id;
                        $model->updated_by = profile()->user_id;
                        $model->save();
                    }
                }
            }
        }

        if ($units && !$titles) {
            foreach ($units as $item) {
                if (OnlineObject::where('course_id', '=', $course_id)
                    ->where('unit_id', '=', $item)
                    ->exists()) {
                    continue;
                }

                if (!Unit::where('id', '=', $item)->exists()) {
                    continue;
                }

                $unit = Unit::find($item);
                $model = new OnlineObject();
                $model->unit_id = $item;
                $model->unit_level = $unit->level;
                $model->type = $type;
                $model->course_id = $course_id;
                $model->created_by = profile()->user_id;
                $model->updated_by = profile()->user_id;
                $model->save();
            }
        }

       /* if ($object_type == 3) {
            if ($areas) {
                foreach ($areas as $area) {
                    $model = new OnlineObject();
                    $model->area1 = $request->post('area1');
                    $model->area2 = $request->post('area2');
                    $model->area3 = $request->post('area3');
                    $model->area4 = $area;
                    $model->type = $type;
                    $model->course_id = $course_id;
                    $model->created_by = profile()->user_id;
                    $model->updated_by = profile()->user_id;
                    $model->save();
                }
            }
            else {
                $model = new OnlineObject();
                $model->area1 = $request->post('area1');
                $model->area2 = $request->post('area2');
                $model->area3 = $request->post('area3');
                $model->area4 = null;
                $model->type = $type;
                $model->course_id = $course_id;
                $model->created_by = profile()->user_id;
                $model->updated_by = profile()->user_id;
                $model->save();
            }
        }*/

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Thêm đối tượng khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 1;
        $history_edit->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'object']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'object';
        $course_edit->course_type = 1;
        $course_edit->save();

        return \response()->json([
            'status' => 'success',
            'message' => 'Thêm đối tượng thành công',
        ]);
    }

    public function getObject($course_id, Request $request)
    {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = OnlineObject::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
            'c.name AS unit_name'
        ]);

        $query->from('el_online_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->where('a.course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->area4) {
                $row->area_name = @Area::find($row->area4)->name;
            }
            elseif ($row->area3) {
                $row->area_name = @Area::find($row->area3)->name;
            }
            elseif ($row->area2) {
                $row->area_name = @Area::find($row->area2)->name;
            }
            else {
                $row->area_name = @Area::find($row->area1)->name;
            }
        }

        json_result([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function removeObject($course_id, Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        OnlineObject::destroy($item);

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Xoá đối tượng khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 1;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveCost($course_id, Request $request)
    {
        $find = [',', ';', '.'];
        $cost_ids = $request->id;
        $plan_amounts = str_replace($find, '', $request->plan_amount);
        $actual_amounts = str_replace($find, '', $request->actual_amount);
        $notes = $request->note;

        foreach ($cost_ids as $key => $cost_id) {
            if (OnlineCourseCost::checkCostExists($course_id, $cost_id)) {
                OnlineCourseCost::updateOrCreate([
                    'course_id'=>$course_id,
                    'cost_id'=>$cost_id
                ], [
                    'course_id'=>$course_id,
                    'cost_id'=>$cost_id,
                    'plan_amount' => (float)$plan_amounts[$key] ? $plan_amounts[$key] : 0,
                    'actual_amount' => (float)$actual_amounts[$key] ? $actual_amounts[$key] : 0,
                    'notes' => $notes[$key] ? $notes[$key] : '',
                ]);
                continue;
            }
            if($plan_amounts[$key] > 0 || $actual_amounts[$key] > 0) {
                $model = new OnlineCourseCost();
                $model->cost_id = $cost_id;
                $model->plan_amount = $plan_amounts[$key] ? $plan_amounts[$key] : 0;
                $model->actual_amount = $actual_amounts[$key] ? $actual_amounts[$key] : 0;
                $model->notes = $notes[$key] ? $notes[$key] : '';
                $model->course_id = $course_id;
                $model->save();
            }
        }

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'cost']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'cost';
        $course_edit->course_type = 1;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Lưu chi phí đào tạo thành công',
        ]);
    }

    public function ajaxGetCourseCode(Request $request)
    {
        $this->validateRequest([
            'subject_id' => 'required',
        ], $request, [
            'subject_id' => 'Mã học phần',
        ]);

        $user_role = UserRole::query()
            ->from('el_user_role as a')
            ->leftJoin('el_roles as b', 'b.id', '=', 'a.role_id')
            ->where('a.user_id', '=', profile()->user_id)
            ->first('b.code');

        $subject_id = $request->input('subject_id');
        $id = $request->id;
        $subject = Subject::find($subject_id);
        $courses = OnlineCourse::where('subject_id', '=', $subject->id)->get();
        $level_subject = LevelSubject::find($subject->level_subject_id);
        $training_program = TrainingProgram::find($subject->training_program_id);

        $count_course = count($courses);

        $check_count_course = '';
        for ($i = 1; $i <= $count_course; $i++) {
            $count = '00'.$i;
            $get_course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
            $check_subject_course_code = OnlineCourse::where('code',$get_course_code)->first();
            if(empty($check_subject_course_code)) {
                $check_count_course = $count;
                break;
            }
        }

        if( !empty($check_count_course) ) {
            $course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$check_count_course;

        } else {
            $count_course = count($courses) + 1;
            $count = '00'.$count_course;
            $course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
        }
        if($id) {
            $get_course_code_subject = OnlineCourse::find($id);
            if($get_course_code_subject->subject_id == $subject_id ) {
                $course_code = $get_course_code_subject->code;
            }
        }

        $color = Config::where('name','color_online')->first();
        $i_text = Config::where('name','i_text_online')->first();
        $b_text = Config::where('name','b_text_online')->first();

        return response()->json([
            'id' => count($courses),
            'course_code' => $course_code,
            'description' => $subject->description,
            'content' => $subject->content,
            'level_subject_name' => @$level_subject->name,
            'training_program_id' => $training_program->id,
            'training_program_code' => $training_program->code,
            'training_program_name' => $training_program->name,
            'color' => !is_null($subject->color) ? $subject->color : ($color ? $color->value : null),
            'i_text' => $subject->i_text == 1 ? $subject->i_text : ($i_text ? $i_text->value : 0),
            'b_text' => $subject->b_text == 1 ? $subject->b_text : ($b_text ? $b_text->value : 0),
            'image' => $subject->image,
            'path_image' => $subject->image ? image_file($subject->image) : '',
        ]);
    }

    public function lockCourse(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);
        $user_invited = null;
        $check_user_invited = OnlineInviteRegister::query()->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }
        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                if ($user_invited && in_array($id, $user_invited)){
                    continue;
                }
                $model = OnlineCourse::findOrFail($id);
                $model->lock_course = $status;
                $model->save();
            }
        } else {
            $model = OnlineCourse::findOrFail($ids);
            $model->lock_course = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' =>trans('laother.successful_save'),
        ]);
    }

    public function getCondition($course_id, Request $request){
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $condition = OnlineCourseCondition::where('course_id', '=', $course_id)->first();
        $activity_condition = $condition ? explode(',', $condition->activity) : [];

        $query = OnlineCourseActivity::query();
        $query->select([
            'a.id',
            'a.name',
            'a.subject_id',
            'a.lesson_id',
            'b.id AS activity_id',
            'b.code AS activity_code',
            'b.icon',
            'b.name AS activity_name',
            'a.subject_id AS subject_id',
            'a.num_order',
            'a.status'
        ]);
        $query->from('el_online_course_activity AS a')
            ->join('el_online_activity AS b', 'b.id', '=', 'a.activity_id')
            ->where('a.course_id', '=', $course_id)
            ->orderBy('a.num_order', 'ASC');

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $setting_percent = OnlineCourseSettingPercent::query()
                ->where('course_id', '=', $course_id)
                ->where('course_activity_id', '=', $row->id)
                ->first();

            $row->score = $setting_percent && !is_null($setting_percent->score) ? $setting_percent->score : '';
            $row->percent = $setting_percent && !is_null($setting_percent->percent) ? $setting_percent->percent : '';

            $row->disabled_acti = '';
            if ($row->activity_id == 1 || $row->activity_id == 2 || $row->activity_id == 7){
                $row->disabled_acti = 'readonly';
            }

            $row->checked = in_array($row->id, $activity_condition) ? 'checked' : '';
            $row->disabled = !in_array($row->id, $activity_condition) ? 'readonly' : '';
        }

        json_result([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function saveCondition($course_id, Request $request)
    {
        $activity = $request->activity;
        $percent = $request->percent;
        $score = $request->score;

        if (array_sum($percent) != 100){
            json_message('Tổng % là 100', 'error');
        }

        OnlineCourseSettingPercent::where('course_id', $course_id)->delete();
        foreach ($activity as $key => $item){
            OnlineCourseSettingPercent::updateOrCreate([
                'course_id' => $course_id,
                'course_activity_id' => $item,
            ], [
                'course_id' => $course_id,
                'course_activity_id' => $item,
                'percent' => isset($percent[$item]) ? $percent[$item] : null,
                'score' => isset($score[$item]) ? $score[$item] : null,
            ]);
        }

        $condition = OnlineCourseCondition::firstOrNew(['course_id' => $course_id]);
        $condition->course_id = $course_id;
        $condition->rating = $request->complaterating ?? 0;
        $condition->orderby = $request->orderby ?? 0;
        $condition->activity = implode(',', $activity);
        $condition->grade_methor = $request->grade_methor ?? null;

        if ($condition->save()) {
            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = profile()->user_id;
            $history_edit->tab_edit = 'Điều kiện hoàn thành';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 1;
            $history_edit->save();

            $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'condition']);
            $course_edit->course_id = $course_id;
            $course_edit->tab_edit = 'condition';
            $course_edit->course_type = 1;
            $course_edit->save();

            json_message(trans('laother.successful_save'));
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function sendMailApprove(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('lamenu.course')
        ]);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $course = OnlineCourse::find($id);
            $users = [];
            if ($course->status != 1) {
                $automail = new Automail();
                $automail->template_code = 'approve_course';
                $automail->params = [
                    'code' => $course->code,
                    'name' => $course->name,
                    'start_date' => get_date($course->start_date),
                    'end_date' => get_date($course->end_date),
                    'url' => route('module.online.management')
                ];
                $automail->users = $users;
                $automail->check_exists = true;
                $automail->check_exists_status = 0;
                $automail->object_id = $course->id;
                $automail->object_type = 'approve_online';
                $automail->addToAutomail();
            }
        }

        json_message('Gửi mail thành công');
    }

    public function sendMailChange(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('lamenu.course')
        ]);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $course = OnlineCourse::find($id);
            $users = OnlineRegister::where('course_id', '=', $id)
                ->where('status', '=', 1)
                ->pluck('user_id')
                ->toArray();

            $automail = new Automail();
            $automail->template_code = 'course_change';
            $automail->params = [
                'code' => $course->code,
                'name' => $course->name,
                'start_date' => get_date($course->start_date),
                'end_date' => get_date($course->end_date),
                'url' => route('module.online.detail', ['id' => $id])
            ];
            $automail->users = $users;
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course->id;
            $automail->object_type = 'course_online_change';
            $automail->addToAutomail();
        }

        json_message('Gửi mail thành công');
    }

    public function getDataHistory($course_id, Request $request)
    {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineHistoryEdit::query();
        $query->select([
            'a.ip_address',
            'a.tab_edit',
            'a.created_at',
            'b.code',
            'b.firstname',
            'b.lastname',
        ]);
        $query->from('el_online_history_edit AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('type', '=', 1);

        $count = $query->count();
        $query->orderBy('a.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->fullname = $row->lastname . ' ' . $row->firstname . ' (' . $row->code . ')';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    //Quản lý upload file
    public function uploadfile(Request $request) {
        $this->validate($request, [
            'filenames' => "required|string",
        ], [
            'filenames' => "file",
        ]);

        $course_id = $request->course_id;
        if ($request->filenames) {
            $model = new OnlineCourseUpload();
            $model->upload = path_upload($request->filenames);
            $model->course_id = $course_id;
            $model->save();

            $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'libraryFile']);
            $course_edit->course_id = $course_id;
            $course_edit->tab_edit = 'libraryFile';
            $course_edit->course_type = 1;
            $course_edit->save();
        }else{
            json_result([
                'status' => 'error',
                'message' => 'Chưa chọn file',
                'redirect' => route('module.online.edit_libraryFile', ['id' => $course_id])
            ]);
        }

        json_result([
            'status' => 'success',
            'message' => 'Đã tải lên thư vện file',
            'redirect' => route('module.online.edit_libraryFile', ['id' => $course_id])
        ]);
    }
    //end quản lý file

    //Thư viên file
    public function getDataLibraryFile($course_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourseUpload::query();
        $query->select('*');
        $query->from('el_online_course_upload as a');
        $query->where('course_id',$course_id);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('upload', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach($rows as $row){
            $row->uploadName = basename($row->upload);
            $row->uploadFile = link_download('uploads/'.$row->upload);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    //end thư viện file

    public function removeLibraryFile(Request $request) {
        $ids = $request->input('ids', null);
        OnlineCourseUpload::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function getChild($course_id, Request $request){
        $unit_id = $request->id;
        $unit = Unit::find($unit_id);

        $childs = Unit::where('parent_code', '=', $unit->code)->get(['id', 'name', 'code']);

        $count_child = [];
        $page_child = [];
        foreach ($childs as $item){
            $count_child[$item->id] = Unit::countChild($item->code);
            $page_child[$item->id] = route('module.online.get_tree_child', ['id' => $course_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }

    public function getTreeChild($course_id, Request $request) {
        $parent_code = $request->parent_code;
        return view('online::backend.online.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    // HỌC VIÊN GHI CHÉP
    public function getUserNoteEvaluate($course_id, Request $request) {
        $search = $request->input('search_note');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineRegisterView::query();
        $query->select([
            'a.user_id',
            'a.user_type',
            'a.course_id',
            'a.status',
            'a.full_name',
            'a.unit_name',
            'a.title_name',
        ]);
        $query->from('el_online_register_view as a');
        $query->where('a.course_id', $course_id);
        $query->where('a.status', 1);
        $query->where('a.user_id', '>', 2);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('e.name', 'like', '%'. $search .'%');
                $sub_query->orWhere('a.full_name', 'like', '%'. $search .'%');
            });
        }
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach($rows as $row){
            $row->fullname = $row->user_type == 1 ? $row->full_name : $row->second_name;
            $check_ratting = OnlineRating::where('user_id',$row->user_id)->exists();
            if($check_ratting) {
                $row->view_evaluate = route('module.online.view_user_note_evaluate', ['id' => $row->user_id,'course_id'
                => $course_id,'type' => 2, 'user_type' => $row->user_type]);
            } else {
                $row->view_evaluate = '';
            }
            $check_note = OnlineCourseNote::where('user_id', $row->user_id)->exists();
            if($check_note) {
                $row->view_note = route('module.online.view_user_note_evaluate', ['id' => $row->user_id,'course_id' =>
                $course_id,'type' => 1, 'user_type' => $row->user_type]);
            } else {
                $row->view_note = '';
            }
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function viewUserNoteEvaluate($id,$course_id,$type, Request $request) {
        $user_type = $request->user_type;

        $get_rating = null;
        if ($type == 1) {
            $get_user_notes_evaluates = OnlineCourseNote::select('note')->where('user_id',$id)->where('user_type', '=', $user_type)->get();
        }
        if ($type == 2) {
            $get_user_notes_evaluates = OnlineComment::select('content')->where('user_id',$id)->where('user_type', '=', $user_type)->get();
            $get_rating = OnlineRating::where('user_id',$id)->first();
        }
        if ($user_type == 1){
            $profile = Profile::where('user_id',$id)->first();
            $fullname = $profile->lastname .' '. $profile->firstname;
        }else{
            $profile = QuizUserSecondary::find($id);
            $fullname = $profile->name;
        }

        $course = OnlineCourse::find($course_id);
        $page_title = $course->name;
        return view('online::backend.online.form.view_note_evaluate',[
            'id' => $id,
            'type' => $type,
            'fullname' => $fullname,
            'get_user_notes_evaluates'=>$get_user_notes_evaluates,
            'get_rating' => $get_rating,
            'course_id' => $course_id,
            'page_title' => $page_title,
            'course' => $course,
            'user_type' => $user_type
        ]);
    }

    public function getContentEvaluate($id,$course_id,Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_type = $request->user_type;

        $query = OnlineComment::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname'
        ]);
        $query->from('el_online_comment as a');
        $query->leftJoin('el_profile as b','b.user_id','=','a.user_id');
        $query->where('a.course_id',$course_id);
        $query->where('a.user_id',$id);
        $query->where('a.user_type',$user_type);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach($rows as $row){
            $row->fullname = $row->lastname . ' ' . $row->firstname;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeContentEvaluate(Request $request) {
        $ids = $request->input('ids', null);
        OnlineComment::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function export($id,$course_id, $user_type)
    {
        return (new EvaluateExport($id,$course_id, $user_type))->download('danh_sach_binh_luan_'. date('d_m_Y') .'.xlsx');
    }

    // HỌC VIÊN HỎI ĐÁP
    public function getUserAskAnswer($course_id,Request $request) {
        $search = $request->input('search_note');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourseAskAnswer::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'c.name as secon_name',
        ]);
        $query->from('el_online_course_ask_answer as a');
        $query->leftJoin('el_profile as b',function ($sub){
            $sub->on('a.user_id_ask','=','b.user_id')
                ->where('a.user_type_ask', '=', 1);
        });
        $query->leftJoin('el_quiz_user_secondary as c',function ($sub){
            $sub->on('a.user_id_ask','=','c.id')
                ->where('a.user_type_ask', '=', 2);
        });
        $query->where('a.course_id',$course_id);
        // $query->where('b.status',1);

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('lastname', 'like', '%'. $search .'%');
                $sub_query->orWhere('firstname', 'like', '%'. $search .'%');
                $sub_query->orWhere('email', 'like', '%'. $search .'%');
                $sub_query->orWhere(\DB::raw('CONCAT(lastname, \' \', firstname)'), 'like', '%' . $search . '%');
            });
        }
        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach($rows as $row){
            $row->fullname = $row->user_type_ask == 1 ? $row->lastname . ' ' . $row->firstname : $row->secon_name;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveAnswer(Request $request) {
        $this->validateRequest([
            'answer' => 'nullable',
            'regid' => 'required',
        ], $request);

        $answer = $request->input('answer');
        $register_id = $request->input('regid');

            if(OnlineCourseAskAnswer::find($register_id)){
                $model = OnlineCourseAskAnswer::find($register_id);
                $model->answer = $answer;
                $model->user_id_answer = profile()->user_id;
                $model->save();
                json_message('ok');
            }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function ajaxIsopenStatus(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Offline',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status');
        foreach ($ids as $id) {
            $model = OnlineCourseAskAnswer::findOrFail($id);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    // BÀI HỌC
    public function getLesson($course_id,Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourseLesson::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_online_course_lesson as a');
        $query->where('a.course_id',$course_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveLesson($course_id, $type, Request $request) {
        // $this->validateRequest([
        //     'lesson_name' => 'required',
        // ], $request, OnlineCourseLesson::getAttributeName());

        $lesson_name = $request->input('lesson_name');
        $lesson_course = OnlineCourseLesson::where('course_id', $course_id)->count();

        $model = new OnlineCourseLesson();
        $model->lesson_name = $lesson_name ?? 'Chủ đề '.($lesson_course + 1);
        $model->course_id = $course_id;
        $model->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'activity-lesson']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'activity-lesson';
        $course_edit->course_type = 1;
        $course_edit->save();

        if($type == 1) {
            $route = route('module.online.course_for_offline.edit_activity_lesson', ['id' => $course_id]);
        } else {
            $route = route('module.online.edit_activity_lesson', ['id' => $course_id]);
        }

        json_result([
            'status' => 'success',
            'message' => 'Thêm thành công',
            'redirect' => $route,
        ]);
    }

    public function removeLesson(Request $request){
        $this->validateRequest([
            'id' => 'required',
        ], $request, [
            'id' => 'Bài học',
        ]);
        $checkActivity = OnlineCourseActivity::where('lesson_id', $request->id)->exists();
        if($checkActivity){
            json_result([
                'status' => 'error',
                'message' => 'Xóa thất bại vì bài học có chứa học phần',
            ]);
        }

        OnlineCourseLesson::find($request->id)->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function getSettingPercent($course_id, Request $request){
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $condition = OnlineCourseCondition::where('course_id', '=', $course_id)->first();
        $activity_condition = $condition ? explode(',', $condition->activity) : [];

        $query = OnlineCourseActivity::query();
        $query->whereIn('id', $activity_condition);
        $query->where('course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $setting_percent = OnlineCourseSettingPercent::query()
                ->where('course_id', '=', $course_id)
                ->where('course_activity_id', '=', $row->id)
                ->first();

            $row->score = $setting_percent && !is_null($setting_percent->score) ? $setting_percent->score : '';
            $row->percent = $setting_percent && !is_null($setting_percent->percent) ? $setting_percent->percent : '';

            $row->disabled = '';
            if ($row->activity_id == 1 || $row->activity_id == 2 || $row->activity_id == 7){
                $row->disabled = 'readonly';
            }
        }

        json_result([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function saveSettingScorePercent($course_id, Request $request){
        $condition = OnlineCourseCondition::where('course_id', '=', $course_id)->first();
        $activity_condition = explode(',', $condition->activity);

        $query = OnlineCourseActivity::query();
        $query->whereIn('id', $activity_condition);
        $query->where('course_id', '=', $course_id);
        $rows = $query->get();

        $percent = $request->percent;
        $score = $request->score;

        if (array_sum($percent) != 100){
            json_message('Tổng % là 100', 'error');
        }

        foreach ($rows as $key => $row){
            OnlineCourseSettingPercent::updateOrCreate([
                'course_id' => $course_id,
                'course_activity_id' => $row->id,
            ], [
                'course_id' => $course_id,
                'course_activity_id' => $row->id,
                'percent' => isset($percent[$row->id]) ? $percent[$row->id] : null,
                'score' => isset($score[$row->id]) ? $score[$row->id] : null,
            ]);
        }

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'setting_percent']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'setting_percent';
        $course_edit->course_type = 1;
        $course_edit->save();

        json_message(trans('laother.successful_save'));
    }

    // SAO CHÉP KHÓA HỌC
    public function copy(Request $request) {

        $ids = $request->input('ids', null);
        $user_role = UserRole::query()
            ->from('el_user_role as a')
            ->leftJoin('el_roles as b', 'b.id', '=', 'a.role_id')
            ->where('a.user_id', '=', profile()->user_id)
            ->first('b.code');

        foreach ($ids as $id) {
            $getCourse = OnlineCourse::findOrFail($id)->toArray();
            $getLessonCourses = OnlineCourseLesson::where('course_id',$id)->get();

            $getActivityFiles = OnlineCourseActivityFile::where('course_id',$id)->get()->toArray();
            $getActivityVideos = OnlineCourseActivityVideo::where('course_id',$id)->get()->toArray();
            $getActivityUrls = OnlineCourseActivityUrl::where('course_id',$id)->get()->toArray();
            $getActivityQuizzes = OnlineCourseActivityQuiz::where('course_id',$id)->get()->toArray();
            $getActivityScorms = OnlineCourseActivityScorm::where('course_id',$id)->get()->toArray();
            $getActivityVirtualClassrooms = VirtualClassroom::where('course_id',$id)->get()->toArray();

            $getCourseCondition = OnlineCourseCondition::where('course_id',$id)->first();

            $saveCourse = new OnlineCourse();
            $saveCourse->fill($getCourse);
            $saveCourse->isopen = 0;
            $saveCourse->status = 2;
            $saveCourse->lock_course = 0;
            $saveCourse->approved_step = null;
            $saveCourse->created_by = profile()->user_id;
            $saveCourse->updated_by = profile()->user_id;
            $saveCourse->unit_by = session()->get('user_unit');

            $courses = OnlineCourse::where('subject_id', '=', $getCourse['subject_id'])->get();
            $subject = Subject::find($getCourse['subject_id']);
            $count_course = count($courses);

            $check_count_course = '';
            for ($i = 1; $i <= $count_course; $i++) {
                $count = '00'.$i;
                $course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
                $check_subject_course_code = OnlineCourse::where('code',$course_code)->first();
                if(empty($check_subject_course_code)) {
                    $check_count_course = $count;
                    break;
                }
            }

            if( !empty($check_count_course) ) {
                $saveCourse->code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$check_count_course;
            } else {
                $count_course = count($courses) + 1;
                $count = '00'.$count_course;
                $saveCourse->code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
            }
            $saveCourse->save();
            CourseStatistic::update_course_insert_statistic($saveCourse->id,1);

            foreach($getLessonCourses as $getLessonCourse) {
                $getActivityCourses = OnlineCourseActivity::where('course_id',$id)->where('lesson_id',$getLessonCourse->id)->get()->toArray();
                $saveLessonCourse = new OnlineCourseLesson();
                $saveLessonCourse->course_id = $saveCourse->id;
                $saveLessonCourse->lesson_name = $getLessonCourse->lesson_name;
                $saveLessonCourse->save();

                foreach($getActivityCourses as $getActivityCourse) {
                    if($getActivityFiles) {
                        foreach($getActivityFiles as $getActivityFile) {
                            if($getActivityFile['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 3) {
                                $saveActivityFile = new OnlineCourseActivityFile();
                                $saveActivityFile->fill($getActivityFile);
                                $saveActivityFile->course_id = $saveCourse->id;
                                $saveActivityFile->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityFile->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityVideos) {
                        foreach($getActivityVideos as $getActivityVideo) {
                            if($getActivityVideo['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 5) {
                                $saveActivityVideo = new OnlineCourseActivityVideo();
                                $saveActivityVideo->fill($getActivityVideo);
                                $saveActivityVideo->course_id = $saveCourse->id;
                                $saveActivityVideo->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityVideo->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityUrls) {
                        foreach($getActivityUrls as $getActivityUrl) {
                            if($getActivityUrl['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 4) {
                                $saveActivityUrl = new OnlineCourseActivityUrl();
                                $saveActivityUrl->fill($getActivityUrl);
                                $saveActivityUrl->course_id = $saveCourse->id;
                                $saveActivityUrl->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityUrl->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityQuizzes) {
                        foreach($getActivityQuizzes as $getActivityQuiz) {
                            if($getActivityQuiz['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 2) {
                                $saveActivityQuiz = new OnlineCourseActivityQuiz();
                                $saveActivityQuiz->fill($getActivityQuiz);
                                $saveActivityQuiz->course_id = $saveCourse->id;
                                $saveActivityQuiz->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityQuiz->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityScorms) {
                        foreach($getActivityScorms as $getActivityScorm) {
                            if($getActivityScorm['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 1) {
                                $saveActivityScorm = new OnlineCourseActivityScorm();
                                $saveActivityScorm->fill($getActivityScorm);
                                $saveActivityScorm->course_id = $saveCourse->id;
                                $saveActivityScorm->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityScorm->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                    if($getActivityVirtualClassrooms) {
                        foreach($getActivityVirtualClassrooms as $getActivityVirtualClassroom) {
                            if($getActivityVirtualClassroom['id'] == $getActivityCourse['subject_id'] && $getActivityCourse['activity_id'] == 6) {
                                $saveActivityVirtualClassroom = new VirtualClassroom();
                                $saveActivityVirtualClassroom->fill($getActivityVirtualClassroom);
                                $saveActivityVirtualClassroom->course_id = $saveCourse->id;
                                $saveActivityVirtualClassroom->code = $getActivityVirtualClassroom['code'] . rand(2,10);
                                $saveActivityVirtualClassroom->save();

                                $saveActivityCourses = new OnlineCourseActivity();
                                $saveActivityCourses->fill($getActivityCourse);
                                $saveActivityCourses->course_id = $saveCourse->id;
                                $saveActivityCourses->subject_id = $saveActivityVirtualClassroom->id;
                                $saveActivityCourses->lesson_id = $saveLessonCourse->id;
                                $saveActivityCourses->save();
                            }
                        }
                    }
                }
            }

            if(!empty($getCourseCondition)) {
                $saveCourseCondition = new OnlineCourseCondition();
                $saveCourseCondition->course_id = $saveCourse->id;
                $saveCourseCondition->rating = $getCourseCondition->rating;
                $saveCourseCondition->orderby = $getCourseCondition->orderby;
                $saveCourseCondition->grade_methor = $getCourseCondition->grade_methor;

                if(!empty($getCourseCondition->activity)) {
                    $activityCourseConditions = explode(',', $getCourseCondition->activity);
                    foreach($activityCourseConditions as $activityCourseCondition) {
                        $getActivityOfCondition = OnlineCourseActivity::find($activityCourseCondition)->toArray();
                        $getIdActivityNewCourse = OnlineCourseActivity::where('activity_id',$getActivityOfCondition['activity_id'])->where('num_order',$getActivityOfCondition['num_order'])->where('course_id', $saveCourse->id)->first();
                        $getIdActivityNewCourses[] = $getIdActivityNewCourse->id;
                    }
                    $saveCourseCondition->activity = implode(",", $getIdActivityNewCourses);
                }

                $saveCourseCondition->save();
            }
        }

        json_message(trans('laother.copy_success'));
    }

    public function imageActivitySave($id, $type, Request $request) {
        $this->validateRequest([
            'image_activity' => 'required',
        ], $request, OnlineCourse::getAttributeName());

        $model = OnlineCourse::find($id);
        if($request->image_activity) {
            $sizes = config('image.sizes.larage');
            $model->image_activity = upload_image($sizes, $request->image_activity);
        }
        $model->save();
        return response()->json([
            'message' => trans('backend.save_success'),
            'status' => 'success'
        ]);
    }

    // ĐÁNH GIÁ KHÓA HỌC
    public function saveRattingCourse($id, Request $request) {
        $model = RattingCourse::firstOrNew(['course_id' => $id, 'type' => 1]);
        $model->course_id = $id;
        $model->teacher = $request->teacher;
        $model->program_content = $request->program_content;
        $model->organization = $request->organization;
        $model->quality_course = $request->quality_course;
        $model->type = 1;
        $model->save();

        return response()->json([
            'message' => trans('backend.save_success'),
            'status' => 'success'
        ]);
    }

    public function modalInfo($id, Request $request){
        $online = OnlineCourse::find($id);
        $created_at2 = get_date($online->created_at, 'H:i d/m/Y');

        $created_by = $online->created_by ? $online->created_by : 2;
        $updated_by = $online->updated_by ? $online->updated_by : 2;
        $user_created = ProfileView::where('user_id', $created_by)->first();
        $user_updated = ProfileView::where('user_id', $updated_by)->first();

        return view('online::modal.modal_info', [
            'created_at2' => $created_at2,
            'user_created' => $user_created,
            'user_updated' => $user_updated,
        ]);
    }

    //Thiết lập tham gia khóa học
    public function getSettingJoin($course_id, Request $request){
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = SettingJoinOnlineCourse::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_setting_join_online_course AS a');
        $query->where('a.course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            if($row->title_id){
                $title = Titles::find($row->title_id);
                $row->title_or_title_rank = $title->name;
            }
            if($row->title_rank_id){
                $title_rank = TitleRank::find($row->title_rank_id);
                $row->title_or_title_rank = $title_rank->name .' ('. trans('lacategory.title_level') .')';
            }

            $row->course = '';
            $course_complete_id = explode(',', $row->course_complete_id);
            $courses = OnlineCourse::whereIn('id', $course_complete_id)->get(['code', 'name']);

            foreach($courses as $course){
                $row->course .= '('.$course->code.') '. $course->name .'<br>';
            }

            $row->auto_register = $row->auto_register == 1 ? trans('latraining.auto_register') : '';
        }

        json_result([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function saveSettingJoin($course_id, Request $request){
        $check_all_title = $request->check_all_title;
        $check_all_title_rank = $request->check_all_title_rank;
        $title_rank = $request->title_rank;
        $titles = $request->title;

        $date_register = $request->date_register;
        $date_register_join_company = $request->date_register_join_company;
        $course_complete = $request->course_complete;

        if($check_all_title == 0 || $check_all_title_rank == 0) {
            if(empty($titles) && empty($title_rank)){
                json_message('Chưa chọn Chức danh hoặc Cấp bậc chức danh', 'error');
            }
        }

        if($titles){
            $titles = explode(',', $titles);
            if($check_all_title == 1) {
                $all_title = Titles::where('status', 1)->whereNotIn('id', $titles)->pluck('id')->toArray();
            } else {
                $all_title = $titles;
            }
            foreach($all_title as $title){
                SettingJoinOnlineCourse::updateOrCreate([
                    'course_id' => $course_id,
                    'title_id' => $title,
                ],[
                    'course_id' => $course_id,
                    'title_id' => $title,
                    'title_rank_id' => null,
                    'course_complete_id' => $course_complete ? implode(',', $course_complete) : null,
                    'date_register' => $date_register,
                    'date_register_join_company' => $date_register_join_company,
                    'auto_register' => 1,
                ]);
            }
        }


        if($title_rank){
            $title_rank = explode(',', $title_rank);
            if($check_all_title_rank == 1) {
                $all_title_rank = TitleRank::where('status', 1)->whereNotIn('id', $title_rank)->pluck('id')->toArray();
            } else {
                $all_title_rank = $title_rank;
            }
            foreach($all_title_rank as $title){
                SettingJoinOnlineCourse::updateOrCreate([
                    'course_id' => $course_id,
                    'title_rank_id' => $title,
                ],[
                    'course_id' => $course_id,
                    'title_id' => null,
                    'title_rank_id' => $title,
                    'course_complete_id' => $course_complete ? implode(',', $course_complete) : null,
                    'date_register' => $date_register,
                    'date_register_join_company' => $date_register_join_company,
                    'auto_register' => 1,
                ]);
            }
        }

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Thêm thiết lập tham gia khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 1;
        $history_edit->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'setting_join']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'setting_join';
        $course_edit->course_type = 1;
        $course_edit->save();

        return \response()->json([
            'status' => 'success',
            'message' => 'Thêm thành công',
        ]);
    }

    public function removeSettingJoin($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        SettingJoinOnlineCourse::destroy($item);

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Xoá Thiết lập tham gia khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 1;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function changeDateRegister($course_id, Request $request){
        $setting_join_id = $request->setting_join;
        $date_register = $request->date_register;

        $model = SettingJoinOnlineCourse::find($setting_join_id);
        $model->date_register = $date_register;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function importSettingJoin($id, Request $request) {
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new SettingJoinImport($id);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.online.edit_setting_join', ['id' => $id]);

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình import ghi danh tự động',
            'redirect' => $redirect,
        ]);
    }

    public function runCronSettingJoin($course_id, Request $request) {
        \Artisan::call('command:setting_join_online '. $course_id);

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình chạy ghi danh tự động',
        ]);
    }
    /******************************************************************************/
    public function reportScorm($course_id, $scorm_id, Request $request)
    {
        if ($request->ajax()){
            $sort = $request->input('sort', 'c.full_name');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);
            $search = $request->input('search');
            $query = ActivityScormAttempt::leftjoin('el_activity_scorm_scores as b','b.attempt_id','=','el_activity_scorm_attempts.id')
                ->leftjoin('el_profile_view as c','c.id','=','el_activity_scorm_attempts.user_id')
                ->where('el_activity_scorm_attempts.activity_id',$scorm_id)
                ->select('el_activity_scorm_attempts.id','c.code','c.full_name','b.score','el_activity_scorm_attempts.attempt',
                    'el_activity_scorm_attempts.created_at as time_start',
                    'el_activity_scorm_attempts.updated_at  as time_finish')->disableCache();
            if ($search){
                $query->where(function ($subquery) use ($search) {
                    $subquery->orWhere('c.code', 'like', '%' . $search . '%');
                    $subquery->orWhere('c.full_name', 'like', '%' . $search . '%');
                    $subquery->orWhere('c.email', 'like', '%'. $search .'%');
                });
            }
            $count = $query->count();
            $query->orderBy($sort, $order)->orderBy('el_activity_scorm_attempts.attempt');
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();
            json_result(['total' => $count, 'rows' => $rows]);
        }else {
            $course = OnlineCourse::find($course_id);
            $scorm_name = OnlineCourseActivity::where('course_id', $course_id)->where('subject_id',$scorm_id)->where('activity_id', 1)->first(['name']);
            return view('online::backend.scorm.report', [
                'course_name' => $course->name,
                'course_id' => $course_id,
                'scorm_name' => @$scorm_name->name,
                'scorm_id' => $scorm_id,
            ]);
        }
    }
    public function removeAttemptScrom($course_id, $scorm_id, Request $request)
    {
        $attempt_ids = $request->input('ids');
        $users = ActivityScormAttempt::whereIn('id',$attempt_ids)->select('user_id')->distinct()->get();
        ActivityScormAttempt::destroy($attempt_ids);
        foreach ($users as $index => $user) {
            ActivityScormScore::where(['user_id'=>$user->user_id])->whereIn('attempt_id',$attempt_ids)->delete();
            $attempts = ActivityScormAttempt::where('activity_id',$scorm_id)->where('user_id',$user->user_id)->select('id','attempt')->orderBy('attempt')->get();
            foreach ($attempts as $i => $attempt) {
                ActivityScormAttempt::where('id',$attempt->id)->update(['attempt'=>++$i]);
            }
            $this->updateGradeAttempt($course_id,$scorm_id,$user->user_id,$attempts);
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
    private function updateGradeAttempt($course_id, $scorm_id, $user_id,$attempts){
        $scorm_setting = OnlineCourseActivityScorm::find($scorm_id);
        if (!$scorm_setting->score_required)
            return false;
        $score=0;$result=0;
        if ($attempts) {
            switch ($scorm_setting->what_grade) {
                //Lần cao nhất
                case 1:
                    $score = ActivityScormScore::where(['user_id' => $user_id, 'activity_id' => $scorm_id])->max('score');
                    break;
                //trung bình
                case 2:
                    $score = ActivityScormScore::where(['user_id' => $user_id, 'activity_id' => $scorm_id])->sum('score') /
                        ActivityScormScore::where(['user_id' => $user_id, 'activity_id' => $scorm_id])->whereUserId($user_id)->count('id');
                    break;
                //lần đầu
                case 3:
                    $score = ActivityScormScore::where(['user_id' => $user_id, 'activity_id' => $scorm_id])->first(['score'])->score;
                    break;
                //lần cuối
                case 4:
                    $score = ActivityScormScore::where(['user_id' => $user_id, 'activity_id' => $scorm_id])->orderBy('id', 'DESC')->first(['score'])->score;
                    break;
            }
        }
        if (!$attempts) {
            $result  = 0;
        }
        else {
            if ($score >= $scorm_setting->min_score_required) {
                $result  = 1;
            }
            else {
                $result = 0;
            }
        }
        $completion = OnlineCourseActivityCompletion::where([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'activity_id' => $scorm_id,
        ])->first();
        $completion->user_id = $user_id;
        $completion->activity_id = $scorm_id;
        $completion->course_id = $course_id;
        $completion->status = $result;
        $completion->save();
    }

    // BÁO CÁO HOẠT ĐỘNG KHẢO SÁT
    public function reportSurvey($course_id, $activityId, Request $request)
    {
        if ($request->ajax()){
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);
            $search = $request->input('search');
            $query = OnlineSurveyUser::leftjoin('el_profile_view as b','b.id','=','el_online_survey_user.user_id')
                ->where('el_online_survey_user.course_activity_id', $activityId)
                ->where('el_online_survey_user.course_id', $course_id)
                ->select(['el_online_survey_user.*', 'b.full_name', 'b.title_name', 'b.unit_name', 'b.code', 'b.email']);

            if ($search){
                $query->where(function ($subquery) use ($search) {
                    $subquery->orWhere('b.code', 'like', '%' . $search . '%');
                    $subquery->orWhere('b.full_name', 'like', '%' . $search . '%');
                    $sub_query->orWhere('b.email', 'like', '%'. $search .'%');
                });
            }

            $count = $query->count();
            $query->orderBy('b.user_id', $order);
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();

            json_result(['total' => $count, 'rows' => $rows]);
        } else {
            $course = OnlineCourse::find($course_id);
            $survey_name = OnlineCourseActivity::findOrFail($activityId)->name;
            return view('online::backend.survey.report', [
                'course_name' => $course->name,
                'course_id' => $course_id,
                'survey_name' => $survey_name,
                'activityId' => $activityId,
            ]);
        }
    }

    // XUẤT BÁO CAO HOẠT ĐỘNG KHẢO SÁT
    public function exportSurvey($course_id, $activityId)
    {
        $activity = OnlineCourseActivity::find($activityId, ['subject_id']);
        return (new ExportActivitySurvey($course_id, $activityId, $activity->subject_id))->download('danh_sach_hoc_vien_hoan_thanh_hoat_dong_khao_sat_'. date('d_m_Y') .'.xlsx');
    }

    // GHI DANH HV VÀO NHIỀU KHOÁ HỌC
    public function importRegisterMultipleCourse(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new RegisterMultiCourseImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.online.management');

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình import ghi danh',
            'redirect' => $redirect,
        ]);
    }

    //Cập nhật lại HV để chạy cron kết quả đào tạo
    public function updateResultByCondition($course_id, Request $request){
        $exists = OnlineRegister::where(['course_id'=>$course_id])->exists();
        if ($exists){
            OnlineRegister::where(['course_id'=>$course_id])
                ->whereNotNull('cron_complete')
                ->update([
                    'cron_complete' => 0
                ]);

            OnlineRegisterView::where(['course_id'=>$course_id])
                ->whereNotNull('cron_complete')
                ->update([
                    'cron_complete' => 0
                ]);

            json_result([
                'status' => 'success',
                'message' => 'Kết quả sẽ được chạy cập nhật lại',
            ]);
        }else{
            json_result([
                'status' => 'warning',
                'message' => 'Chưa có HV được ghi danh',
            ]);
        }
    }

    // LƯU ĐIỀU KIỆN TIÊN QUYẾT
    public function savePrerequisite($id, Request $request)
    {
        $this->validateRequest([
            'subject_prerequisite' => 'required|'
        ], $request, [
            'subject_prerequisite' => 'Khóa học cần hoàn thành'
        ]);

        if($request->status_title && !$request->title_id) {
            json_message('Chưa chọn chức danh', 'error');
        }

        $status_title = $request->status_title ? 1 : 0;
        $status_date_title_appointment = $request->status_date_title_appointment ? 1 : 0;
        $status_join_company = $request->status_join_company ? 1 : 0;

        $save = SubjectPrerequisiteCourse::firstOrNew(['course_id' => $id, 'course_type' => 1]);
        $save->course_id = $id;
        $save->course_type = 1;
        $save->subject_prerequisite = $request->subject_prerequisite;
        $save->date_finish_prerequisite = $request->date_finish_prerequisite ? $request->date_finish_prerequisite : 0;
        $save->finish_and_score = $request->finish_and_score;
        $save->score_prerequisite = $request->score_prerequisite ? $request->score_prerequisite : 0;
        $save->select_subject_prerequisite = $request->select_subject_prerequisite;
        $save->status_title = $status_title;
        $save->title_id = $request->title_id;
        $save->select_title = $request->select_title;
        $save->status_date_title_appointment = $status_date_title_appointment;
        $save->date_title_appointment = $request->date_title_appointment;
        $save->select_date_title_appointment = $request->select_date_title_appointment;
        $save->status_join_company = $status_join_company;
        $save->join_company = $request->join_company;
        $save->save();

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }

    // LƯU ĐIỀU KIỆN GHI DANH
    public function saveConditionRegister($id, Request $request)
    {
        $save = OnlineCourse::find($id);
        $save->survey_register = $request->survey_register;
        $save->register_quiz_id = $request->register_quiz_id;
        $save->save();

        $offline_course_view = OnlineCourseView::where(['id' => $id])->update([
            'survey_register' => $request->survey_register,
            'register_quiz_id' => $request->register_quiz_id,
        ]);

        $course_view = CourseView::where(['course_id' => $id, 'course_type' => 1])->update([
            'survey_register' => $request->survey_register,
            'register_quiz_id' => $request->register_quiz_id,
        ]);

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
        ]);
    }

    //Tài liệu học tập
    public function uploadDocument($course_id, Request $request) {
        $this->validateRequest([
            'name' => 'required|string',
            'document' => 'required|string',
        ], $request, [
            'name' => 'Tên tài liệu',
            'document' => 'File đính kèm',
        ]);

        $model = OnlineCourseDocument::firstOrNew(['id' => $request->id]);
        $model->name = $request->name;
        $model->document = path_upload($request->document);
        $model->course_id = $course_id;
        $model->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'document']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'document';
        $course_edit->course_type = 1;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
            'redirect' => route('module.online.edit_document', ['id' => $course_id])
        ]);
    }
    public function getDataDocument($course_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineCourseDocument::query();
        $query->where('course_id',$course_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach($rows as $row){
            $row->document_name = basename($row->document);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function removeDocument(Request $request) {
        $ids = $request->input('ids', null);

        OnlineCourseDocument::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
