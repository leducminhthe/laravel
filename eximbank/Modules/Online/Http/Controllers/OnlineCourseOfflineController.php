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
use App\Models\CourseTabEdit;

class OnlineCourseOfflineController extends Controller
{
    public function index() {
        return view('online::backend.online.index2');
    }

    public function getData(Request $request)
    {
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
            'el_online_course.start_date',
            'el_online_course.end_date',
            'el_online_course.status',
            'c.name as subject_name',
            'el_online_course.created_at',
            'el_online_course.approved_step',
            'el_online_course.created_by',
            'el_online_course.updated_by',
        ]);
       $query->leftJoin('el_subject AS c', 'c.id', '=', 'el_online_course.subject_id');
       $query->where('offline', 1);

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
            $row->edit_url = route('module.online.course_for_offline.edit', ['id' => $row->id]);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');

            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);

            $row->info_url = route('module.online.modal_info', ['course_id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null)
    {
        $titles = Titles::select(['id','name','code'])->where('status', '=', 1)->get();
        $user_invited = false;

        $training_objects = TrainingObject::where('status',1)->get(['id','name']);
        $training_costs = TrainingCost::orderBy('type')->get(['id','name','type']);
        $course_cost = OnlineCourseCost::where('course_id', '=', $id)->get(['actual_amount','notes','plan_amount']);
        $total_actual_amount = OnlineCourseCost::getTotalActualAmount($id);
        $total_plan_amount = OnlineCourseCost::getTotalPlanAmount($id);
        // $teachers = TrainingTeacher::get();
        $templates = RatingTemplate::get(['id','name']);
        $plan_app_template = PlanAppTemplate::get();
        $training_plan = TrainingPlan::get(['id','name']);
        $certificate = Certificate::get(['id','code']);
        $qrcode_survey_after_course = null;
        $training_forms = TrainingForm::where('training_type_id',1)->get(['id','name']);

        $units = Unit::select(['id','name','code'])->where('status', '=', 1)->get();

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

            return view('online::backend.online.form2', [
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
            ]);
        }

        $model = new OnlineCourse();
        $page_title = trans('labutton.add_new') ;
        $permission_save = userCan(['online-course-create', 'online-course-edit']);

        return view('online::backend.online.form2', [
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
        ]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'code' => 'required|unique:el_online_course,code,' . $request->id,
            'name' => 'required',
            'start_date' => 'required|date_format:d/m/Y',
        ], $request, OnlineCourse::getAttributeName());
        $subject = Subject::find($request->subject_id);

        $model = OnlineCourse::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        $model->start_date = date_convert($request->input('start_date'));
        $model->level_subject_id = @$subject->level_subject_id;

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
            $date_original = OnlineCourse::where(['id' => $request->post('id')])->value('start_date');
        }

        $model->updated_by = profile()->user_id;
        $model->offline = 1;
        $model->training_form_id = 1;
        $model->course_action = 1;
        $save = $model->save();
        if ($save) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.online.course_for_offline.edit', ['id' => $model->id])
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

        return response()->json([
            'id' => count($courses),
            'course_code' => $course_code,
            'level_subject_name' => @$level_subject->name,
            'training_program_id' => $training_program->id,
            'training_program_code' => $training_program->code,
            'training_program_name' => $training_program->name,
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
        $subjects = Subject::where('training_program_id', '=', $training_program_id)->where('subsection', 1)->get();
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
                $model = OnlineCourse::findOrFail($id);
                $model->isopen = $status;
                $model->save();
            }
        } else {
            $model = OnlineCourse::findOrFail($ids);
            $model->isopen = $status;
            $model->save();
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

    public function saveLesson($course_id, Request $request) {
        $this->validateRequest([
            'lesson_name' => 'required',
        ], $request, OnlineCourseLesson::getAttributeName());

        $lesson_name = $request->input('lesson_name');
        $model = new OnlineCourseLesson();
        $model->lesson_name = $lesson_name;
        $model->course_id = $course_id;
        $model->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'activity-lesson']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'activity-lesson';
        $course_edit->course_type = 1;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.online.edit_activity_lesson', ['id' => $course_id]),
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
}

