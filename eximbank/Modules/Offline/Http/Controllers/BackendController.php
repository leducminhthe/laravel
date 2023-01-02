<?php

namespace Modules\Offline\Http\Controllers;

use App\Models\Automail;
use App\Models\Config;
use App\Models\CourseStatistic;
use App\Models\CourseView;
use App\Models\Categories\Area;
use App\Models\Categories\CommitMonth;
use App\Models\Categories\District;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Province;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Unit;
use App\Models\Profile;
use App\Scopes\DraftScope;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Certificate\Entities\Certificate;
use Modules\Indemnify\Entities\Indemnify;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineCourseUpload;
use Modules\Offline\Entities\OfflineCourseView;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineRegisterView;
use Modules\Offline\Entities\OfflineResult;
use Modules\Offline\Entities\OfflineScheduleParent;
use Modules\Offline\Entities\OfflineStudentCost;
use Modules\Offline\Entities\OfflineCondition;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Online\Entities\OnlineHistoryEdit;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\PlanApp\Entities\PlanAppTemplate;
use Modules\Quiz\Entities\Quiz;
use Modules\Rating\Entities\RatingTemplate;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TeacherType;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\StudentCost;
use App\Models\Categories\TitleRank;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingType;
use App\Models\Categories\TrainingObject;
use App\Models\ProfileView;
use Modules\ReportNew\Entities\ReportNewExportBC05;
use Modules\ReportNew\Entities\ReportNewExportBC08;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use Modules\ReportNew\Entities\ReportNewExportBC26;
use Modules\TrainingPlan\Entities\TrainingPlan;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use App\Models\TypeCost;
use App\Models\RattingCourse;
use Modules\UserPoint\Entities\UserPointItem;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Offline\Entities\SettingJoinOfflineCourse;
use App\Models\CourseTabEdit;
use Modules\Offline\Imports\RegisterMultiCourseImport;
use Modules\Offline\Imports\ResultMultiCourseImport;
use Modules\Offline\Entities\OfflineTeachingOrganizationTemplate;
use Modules\Offline\Entities\OfflineTeachingOrganizationCategory;
use Modules\Offline\Entities\OfflineTeachingOrganizationQuestion;
use Modules\Offline\Entities\OfflineTeachingOrganizationAnswer;
use Modules\Offline\Entities\OfflineTeachingOrganizationAnswerMatrix;
use Modules\Rating\Entities\RatingCategory;
use Modules\Rating\Entities\RatingQuestion;
use Modules\Rating\Entities\RatingQuestionAnswer;
use Modules\Rating\Entities\RatingAnswerMatrix;
use Modules\Survey\Entities\Survey;
use App\Models\SubjectPrerequisiteCourse;
use App\Models\SubjectPrerequisite;
use Modules\Offline\Imports\SettingJoinImport;
use Modules\Offline\Entities\OfflineCourseDocument;
use Modules\PermissionApproved\Entities\PermissionApproved;

class BackendController extends Controller
{
    public $is_unit = 0;

    public function index() {
        \Session::forget('errors');
        $training_form = TrainingForm::where('training_type_id',2)->get();

        return view('backend.training.index',[
            'training_forms' => $training_form,
        ]);
    }

    public function getData(Request $request) {
        $user_unit = session()->get('user_unit');
        $parent_unit = getParentUnit($user_unit);
        $count_level_permission_approve = PermissionApproved::where(['unit_id' => $parent_unit, 'model_approved' => 'el_offline_course'])->count();

        $date = date('Y-m-d');
        $search = $request->input('search');
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        $subject_id = $request->input('subject_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $training_form_id = $request->input('training_form_id');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_invited = null;
        $check_user_invited = OfflineInviteRegister::query()->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }

        $prefix= \DB::getTablePrefix();
        OfflineCourse::addGlobalScope(new DraftScope(null, null, $user_invited));
        $query = OfflineCourse::query();
        $query->select([
            'el_offline_course.id',
            'el_offline_course.name',
            'el_offline_course.code',
            'el_offline_course.in_plan',
            'el_offline_course.isopen',
            'el_offline_course.register_deadline',
            'el_offline_course.start_date',
            'el_offline_course.end_date',
            'el_offline_course.status',
            'el_offline_course.lock_course',
            'el_offline_course.created_at',
            'el_offline_course.approved_step',
            'el_offline_course.created_by',
            'el_offline_course.updated_by',
            'el_offline_course.convert_course_plan',
            'el_offline_course.template_rating_teacher_id',
            'c.name as subject_name',
        ]);
        $query->leftJoin('el_subject AS c', 'c.id', '=', 'el_offline_course.subject_id');

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_offline_course.name', 'like', '%'. $search .'%');
                $subquery->orWhere('el_offline_course.code', 'like', '%'. $search .'%');
            });
        }
        if ($training_form_id){
            $query->where('el_offline_course.training_form_id', '=', $training_form_id);
        }
        if ($training_program_id){
            $query->where('el_offline_course.training_program_id', '=', $training_program_id);
        }

        if ($subject_id){
            $query->where('el_offline_course.subject_id', '=', $subject_id);
        }

        if ($start_date) {
            $query->where('el_offline_course.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('el_offline_course.start_date', '<=', date_convert($end_date, '23:59:59'));
        }
        if ($level_subject_id){
            $query->where('level_subject_id', '=', $level_subject_id);
        }

        $count = $query->count();
        $query->orderBy('el_offline_course.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            if($row->status == 1) {
                if($date < get_date($row->start_date, 'Y-m-d')) {
                    $row->status_approve = trans('latraining.not_start_yet');
                } else if ($date >= get_date($row->start_date, 'Y-m-d') && $date <= get_date($row->end_date, 'Y-m-d')) {
                    $row->status_approve = trans('latraining.happenning');
                } else if ($date > get_date($row->end_date, 'Y-m-d') && $row->lock_course == 0) {
                    $row->status_approve = trans('latraining.wait_test');
                } else if ($row->lock_course == 1) {
                    $row->status_approve = trans('latraining.over');
                }
            }

            $row->check_class = OfflineCourseClass::whereCourseId($row->id)->count();

            $row->edit_url = route('module.offline.edit', ['id' => $row->id]);
            $row->register_url = route('module.offline.register.default', ['id' => $row->id]);
            $row->register_class_url = route('module.offline.modal_class', ['course_id' => $row->id, 'obj' => 'register']);

            $row->register_deadline = get_date($row->register_deadline);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');

            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);

            $row->info_url = route('module.offline.modal_info', ['course_id' => $row->id]);

            if($row->template_rating_teacher_id && $row->status == 1 && $row->isopen == 1) {
                $qrcode = route('qrcode_process',['id' => $row->id, 'type' => 'rating-teaching-organization']);
                $row->qrcode = \QrCode::size(300)->generate($qrcode);

                $row->teaching_organization_url = route('module.offline.teaching_organization.index', ['course_id' => $row->id]);
            } else {
                $row->qrcode = '';
            }

            $row->count_level_permission_approve = $count_level_permission_approve;
            $row->parent_unit = $parent_unit;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null) {
		RatingTemplate::addGlobalScope(new DraftScope());

        $errors = session()->get('errors');
        \Session::forget('errors');

        $training_partners = TrainingPartner::select(['id','code','name'])->get();
        $areas = Area::select(['id','code','name'])->where('status', '=', 1)->get();
        $user_invited = false;
        $title_rank = TitleRank::select(['id','name','code'])->where('status', '=', 1)->get();
        $titles = Titles::select(['id','name','code'])->where('status', '=', 1)->get();
        $training_costs = TrainingCost::orderBy('type')->orderBy('id')->get(['id','name','type']);
        $type_costs = TypeCost::get(['id','name']);
        $registers = OfflineStudentCost::getStudent($id);
        $total_actual_amount = OfflineStudentCost::getTotalActualAmount($id);
        $total_plan_amount = OfflineStudentCost::getTotalPlanAmount($id);
        $course_costs = OfflineCourseCost::where('course_id', '=', $id)->get(['id']);
        $condition = OfflineCondition::where('course_id', '=', $id)->first();
        $trainingTeachers = TrainingTeacher::where('status', 1)->get();
        $templates = RatingTemplate::where('teaching_organization', 0)->get(['id','name']);
        $templates_rating_teacher = RatingTemplate::where('teaching_organization', 1)->get(['id','name']); //Lấy mẫu có chọn đánh giá GV
        $plan_app_template = PlanAppTemplate::get();
        $training_plan = TrainingPlan::get(['id','name']);
        $quizs = Quiz::where('status','=',1)->get(['id','name']);
        $province = Province::get(['id','name','code']);
        $certificate = Certificate::get(['id','code']);
        $qrcode_survey_after_course = null;
        $training_forms = TrainingForm::where('training_type_id',2)->get(['id','name']);
        $training_objects = TrainingObject::where('status',1)->get(['id','name']);

        $survey_register = Survey::query()
        ->select(['survey.id','survey.name'])
        ->from('el_survey as survey')
        ->join('el_survey_template2 as template', 'template.survey_id', '=', 'survey.id')
        ->where('survey.type', 3)
        ->where('survey.status', 1)
        ->get();

        $units = Unit::select(['id','name','code'])->where('status', '=', 1)->get();

        $corporations = Unit::select(['id','name','code'])->where('level', '=', 1)->where('status', '=', 1)->get();

        $qrcode_entrance_quiz = null;
        $qrcode_end_quiz = null;

        if ($id) {
            $courseTabEdit = CourseTabEdit::where('course_id', $id)->where('course_type', 2)->pluck('tab_edit')->toArray();
            $ratting_course = RattingCourse::where('course_id',$id)->where('type',2)->first();
            $model = OfflineCourse::find($id);
            $course = $model;
            if (!$model) abort(404);
            $training_location_course = TrainingLocation::select('province_id','district_id')->find($model->training_location_id);

            if ($training_location_course){
                $district = District::query()->where('province_id','=',$training_location_course->province_id)->get(['id','name']);

                $training_location = TrainingLocation::where('province_id','=',$training_location_course->province_id)
                    ->where('district_id','=',$training_location_course->district_id)
                    ->where('status','=',1)
                    ->get(['id','name']);

                $model->training_location_province = $training_location_course->province_id;
                $model->training_location_district = $training_location_course->district_id;
            }else{
                $district=null;
                $training_location=null;
            }

            $page_title = $model->name;
            $subject = Subject::where('id', $model->subject_id)->first();
            $unit = explode(',', $model->unit_id);

            $training_program = TrainingProgram::where('id', $model->training_program_id)->first();

            $course_time = $model->course_time;
            $course_time_unit = preg_replace("/[^a-z]/", '', $model->course_time_unit);

            $student_cost = function ($regid){
                return OfflineStudentCost::getTotalStudentCost($regid);
            };

            $exemption = function ($user_id, $course_id){
                return Indemnify::sumCommitAmount($user_id, $course_id);
            };

            $qrcode_survey_after_course = json_encode(['course'=>$id,'course_type'=>2,'survey'=>$model->template_id,'type'=>'survey_after_course']);

            $qrcode_entrance_quiz = json_encode(['course'=>$id,'course_type'=>2,'quiz_id'=>$model->entrance_quiz_id,'type'=>'entrance_quiz']);
            $qrcode_end_quiz = json_encode(['course'=>$id,'course_type'=>2,'quiz_id'=>$model->quiz_id,'type'=>'end_quiz']);

            $level_subject = LevelSubject::find($model->level_subject_id);

            !empty($model->document) ? $documents = json_decode($model->document) : $documents = [];
            !empty($model->training_area_id) ? $training_area = json_decode($model->training_area_id) : $training_area = [];
            !empty($model->training_object_id) ? $get_training_object_id = json_decode($model->training_object_id) : $get_training_object_id = [];
            !empty($model->training_unit) ? $training_unit = json_decode($model->training_unit) : $training_unit = [];
            !empty($model->training_partner_id) ? $training_partner = json_decode($model->training_partner_id) : $training_partner = [];

            $teacher_type = TeacherType::where('id', $model->teacher_type_id)->first(['id','name']);

            $check_user_invited = OfflineInviteRegister::query()
                ->where('course_id', '=', $id)
                ->where('user_id', '=', profile()->user_id);
            if ($check_user_invited->exists()){
                $user_invited = true;
            }
            $this->saveIndemnify($id);
            $course_costs_id = OfflineCourseCost::where('course_id', '=', $id)->pluck('cost_id')->toArray();

            $userpointOthers = UserPointItem::where("type","=",3)->where('ikey', '!=', 'offline_complete')->get();

            $settings = UserPointSettings::where("item_id", "=", $id)->where("item_type","=",3)->get();
            $settingComplete = array();
            $settingModules = array();
            $settingOthers = array();

            if($settings->count() > 0){
                foreach ($settings as $k=>$v){
                    if($v->pkey=='offline_complete')
                        $settingComplete[] = $v;
                    else if($v->pkey=='offline_quiz_complete'){
                        $settingModules[] = $v;
                    }
                    else {
                        $settingOthers[$v->pkey] = $v;
                    }
                }
            }else{
                $user_point_offline_complete = UserPointItem::where("type","=",3)->where('ikey', '=', 'offline_complete')->first();

                $start_date= strtotime($model->start_date);
                $end_date= strtotime($model->end_date);

                $complete = new UserPointSettings();
                $complete->pkey = 'offline_complete';
                $complete->item_id = $id;
                $complete->item_type = 3;
                $complete->pvalue = $user_point_offline_complete->default_value;
                $complete->start_date = $start_date;
                $complete->end_date = $end_date;
                $complete->save();

                $settingComplete[] = $complete;

                foreach ($userpointOthers as $k => $item){
                    $complete = new UserPointSettings();
                    $complete->pkey = $item->ikey;
                    $complete->item_id = $id;
                    $complete->item_type = 3;
                    $complete->pvalue = $item->default_value;
                    $complete->save();

                    $settingOthers[$item->ikey] = $complete;
                }
            }
            // $offlineTeacher = OfflineTeacher::where('course_id', $id)->pluck('teacher_id')->toArray();
            $getTrainingTeacher  = DB::query()
                ->select('el_training_teacher.*')
                ->from('el_training_teacher')
                ->whereExists(function($sub) use ($id) {
                    $sub->select(['el_offline_course_teachers.id'])
                    ->from('el_offline_course_teachers')
                    ->whereColumn('teacher_id', '=', 'el_training_teacher.id')
                    ->where('course_id', $id);
                })->get();

            //thiết lập khóa học hoàn thành trước
            $course_others = OfflineCourse::where('id', '!=', $id)->where('status', 1)->get();
            $class = OfflineCourseClass::where(['default'=>1,'course_id'=>$model->id])->first();
            $offline_result = OfflineResult::whereCourseId($id)->exists();

            $offline_teaching_organization_template = OfflineTeachingOrganizationTemplate::where('course_id', $model->id)->first();

            // ĐIỀU KIỆN TIÊN QUYẾT
            $subject_prerequisite = '';
            $title_prerequisite = '';
            $prerequisite_course = SubjectPrerequisiteCourse::where(['course_id' => $id, 'course_type' => 2])->first();
            if(!isset($prerequisite_course)) {
                $prerequisite_course = SubjectPrerequisite::where('subject_id', $model->subject_id)->first();
            }
            $subject_prerequisite = Subject::where('id', @$prerequisite_course->subject_prerequisite)->first(['id','name']);
            $title_prerequisite = Titles::where('id', @$prerequisite_course->title_id)->first(['id','name']);

            $entrance_quiz = Quiz::find(@$model->entrance_quiz_id);
            $end_quiz = Quiz::find(@$model->quiz_id);
            //Buổi học elearning
            $check_schedule_elearning = OfflineSchedule::where('course_id', $id)->where('type_study', 3)->exists();

            $register_quiz = Quiz::where(['course_id' => $id, 'course_type' => 2, 'quiz_type_by_offline' => 'register_quiz_id'])->first(['id', 'name']);

            return view('offline::backend.offline.form', [
                'title_rank' => $title_rank,
                'titles' => $titles,
                'model' => $model,
                'page_title' => $page_title,
                'subject' => $subject,
                'training_program' => $training_program,
                'training_costs' => $training_costs,
                'registers' => $registers,
                'total_actual_amount' => $total_actual_amount,
                'total_plan_amount' => $total_plan_amount,
                'course_costs' => $course_costs,
                'condition' => $condition,
                'trainingTeachers' => $trainingTeachers,
                'templates' => $templates,
                'templates_rating_teacher' => $templates_rating_teacher,
                'plan_app_template'=>$plan_app_template,
                'province'=>$province,
                'district'=>$district,
                'training_location'=>$training_location,
                'training_plan' => $training_plan,
                'quizs'=>$quizs,
                'is_unit' => $model->unit_id,
                'training_forms' => $training_forms,
                'student_cost' => $student_cost,
                'course_time' => $course_time,
                'course_time_unit' => $course_time_unit,
                'unit' => $unit,
                'exemption' => $exemption,
                'certificate' => $certificate,
                'setting' => null,
                'qrcode_survey_after_course' => $qrcode_survey_after_course,
                'units' => $units,
                'level_subject' => $level_subject,
                'corporations' => $corporations,
                'training_area' => $training_area,
                'training_partner' => $training_partner,
                'get_training_object_id' => $get_training_object_id,
                'training_objects' => $training_objects,
                'teacher_type' => $teacher_type,
                'user_invited' => $user_invited,
                'documents' => $documents,
                'areas' => $areas,
                'type_costs' => $type_costs,
                'course_costs_id' => $course_costs_id,
                'ratting_course' => $ratting_course,
                'training_unit' => $training_unit,
                'training_partners' =>$training_partners,
                'setting_complete' => $settingComplete,
                'setting_modules' => $settingModules,
                'setting_others' => $settingOthers,
                'userpoint_others' => $userpointOthers,
                'course_others' => $course_others,
                'class' => $class,
                'course' => $course,
                'getTrainingTeacher' => $getTrainingTeacher,
                'courseTabEdit' => $courseTabEdit,
                'offline_result' => $offline_result,
                'qrcode_entrance_quiz' => $qrcode_entrance_quiz,
                'qrcode_end_quiz' => $qrcode_end_quiz,
                'offline_teaching_organization_template' => $offline_teaching_organization_template,
                'survey_register' => $survey_register,
                'prerequisite_course' => $prerequisite_course,
                'subject_prerequisite' => $subject_prerequisite,
                'title_prerequisite' => $title_prerequisite,
                'entrance_quiz' => $entrance_quiz,
                'end_quiz' => $end_quiz,
                'register_quiz' => $register_quiz,
                'check_schedule_elearning' => $check_schedule_elearning,
            ]);
        }

        $model = new OfflineCourse();
        $page_title = trans('labutton.add_new') ;
        $training_location = TrainingLocation::all();
        return view('offline::backend.offline.form', [
            'title_rank' => $title_rank,
            'titles' => $titles,
            'model' => $model,
            'page_title' => $page_title,
            'plan_app_template'=>$plan_app_template,
            'province'=>$province,
            'district'=>null,
            'training_location'=>$training_location,
            'training_plan' => $training_plan,
            'quizs'=>$quizs,
            'is_unit' => $this->is_unit,
            'templates' => $templates,
            'templates_rating_teacher' => $templates_rating_teacher,
            'training_forms' => $training_forms,
            'course_time' => null,
            'course_time_unit' => null,
            'certificate' => $certificate,
            'qrcode_survey_after_course' => $qrcode_survey_after_course,
            'units' => $units,
            'corporations' => $corporations,
            'user_invited' => $user_invited,
            'areas' => $areas,
            'type_costs' => $type_costs,
            'training_objects' => $training_objects,
            'training_partners' =>$training_partners,
            'trainingTeachers' => $trainingTeachers,
            'qrcode_entrance_quiz' => $qrcode_entrance_quiz,
            'qrcode_end_quiz' => $qrcode_end_quiz,
            'survey_register' => $survey_register,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'code' => 'required|unique:el_offline_course,code,'. $request->id,
            'name' => 'required',
            'category_id' => 'nullable|exists:el_course_categories,id',
            'in_plan' => 'nullable|exists:el_training_plan,id',
            'course_time' => 'nullable',
            'status' => 'nullable',
            'image' => 'nullable|string',
            'document' => "nullable|array|min:1",
            'document.*' => 'nullable|max:2048576',
            "teacher_course"    => "required|array|min:1",
            "teacher_course.*"  => "required|string|distinct|min:1",
            'num_lesson' => 'nullable',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
            'training_location_id'=>'nullable|integer',
            'training_unit'=>'nullable|max:256',
            'coefficient'=>'required_if:commit,1|nullable|integer|min:1|max:100',
            'training_form_id' => 'required',
        ], $request, OfflineCourse::getAttributeName());

        $document_upload = [];
        if (!empty($request->hidden_document)) {
            $document_upload = $request->hidden_document;
        }
        if ($request->hasfile('document')) {
            foreach ($request->file('document') as $file) {
                $folder_id = '';

                if (empty($folder_id)) {
                    $folder_id = null;
                }

                $type = 'file';
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $new_filename = Str::slug(basename($filename, "." . $extension)) . '-' . time() . '.' . $extension;

                $storage = \Storage::disk('upload');
                $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);

                if ($new_path) {
                    $warehouse = new Warehouse();
                    $warehouse->file_name = $file->getClientOriginalName();
                    $warehouse->file_type = $file->getMimeType();
                    $warehouse->file_path = $new_path;
                    $warehouse->file_size = $file->getSize();
                    $warehouse->extension = $file->getClientOriginalExtension();
                    $warehouse->source = 'upload';
                    $warehouse->type = $type;
                    $warehouse->created_by = profile()->user_id;
                    $warehouse->updated_by = profile()->user_id;
                    $warehouse->user_id = profile()->user_id;
                    $warehouse->folder_id = $folder_id;
                    $warehouse->save();
                    $document_upload[] = $new_path;
                }
            }
        }

        if(!$request->has('commit')){
            $request->merge([
                'commit' => "0",
            ]);
        }
        $course_time_unit = $request->course_time_unit;
        $unit_id = $request->post('unit_id');

        if ($request->post('id')){
            $check_schedule = OfflineSchedule::where('course_id', '=', $request->post('id'))->exists();
            if ($check_schedule){
                $min_lesson_date = OfflineSchedule::where('course_id', '=', $request->post('id'))->min('lesson_date');
                $max_lesson_date = OfflineSchedule::where('course_id', '=', $request->post('id'))->max('lesson_date');

                if ( get_date($request->input('start_date'), 'Y-m-d 00:00:00') > get_date($min_lesson_date, 'Y-m-d H:i:s')){
                    json_message('Đã có lịch học. Thời gian bắt đầu phải trước lịch học', 'error');
                }
                if ( get_date($request->input('end_date'), 'Y-m-d 23:59:59') < get_date($max_lesson_date, 'Y-m-d H:i:s')){
                    json_message('Đã có lịch học. Thời gian kết thúc phải sau lịch học', 'error');
                }
            }
        }

        if(date_convert($request->input('start_date')) > date_convert($request->input('end_date'), '23:59:59')){
            json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
        }

        if ($request->input('register_deadline')){
            if(date_convert($request->input('register_deadline')) > date_convert($request->input('end_date'), '23:59:59')){
                json_message('Hạn đăng ký phải trước Ngày kết thúc', 'error');
            }
        }

        if(isset($request->entrance_quiz_id) && isset($request->quiz_id) && $request->entrance_quiz_id == $request->quiz_id){
            json_message('Thi đầu vào phải khác Thi cuối khóa', 'error');
        }

        if($request->template_rating_teacher_id){
            $ratingcategories = RatingCategory::query()->where('template_id', $request->template_rating_teacher_id)->where('rating_teacher', 1);
            if(!$ratingcategories->exists()){
                json_message('Mẫu công tác tổ chức giảng dạy không có đánh giá GV', 'error');
            }
        }

        $subject = Subject::find($request->subject_id);

        $model = OfflineCourse::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->max_student = $request->max_student ?? 0;
        $model->has_cert = $request->input('has_cert') ? $request->input('has_cert') : 0;
        $date_original = null;
        if (empty($model->id)) {
            $model->created_by = profile()->user_id;
            $model->status = 2;
        }else{
            $date_original = OfflineCourse::where(['id' => $request->post('id')])->value('start_date');
        }

        $model->updated_by = profile()->user_id;

        $model->training_type_id = 2;
        $model->register_deadline = $request->input('register_deadline') ? date_convert($request->input('register_deadline'), '23:59:59') : null;
        $model->start_date = date_convert($request->input('start_date'));
        $model->end_date = date_convert($request->input('end_date'), '23:59:59');
        $model->commit_date = date_convert($request->input('commit_date'), '00:00:00');
        if($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image, 'offline');
        }
        // $model->document = path_upload($model->document);
        $model->document = is_array($document_upload) ? json_encode($document_upload) : '';
        $model->unit_id = is_array($unit_id) ? implode(',', $unit_id) : null;
        $model->course_time = $request->input('course_time');
        $model->course_time_unit = $course_time_unit;
        $model->level_subject_id = $subject->level_subject_id;
        $model->rating_end_date = $request->input('rating_end_date') ? date_convert($request->input('rating_end_date'), '23:59:59') : null;
        $model->training_area_id = is_array($request->training_area_id) ? json_encode($request->training_area_id) : '';
        $model->training_object_id = is_array($request->training_object_id) ? json_encode($request->training_object_id) : '';
        $model->training_unit = is_array($request->training_unit) ? json_encode($request->training_unit) : '';
        $model->training_partner_id = is_array($request->training_partner_id) ? json_encode($request->training_partner_id) : '';

        if ($model->save()) {
            $this->saveTeacher($request->teacher_course, $model->id);

            $course_edit = CourseTabEdit::firstOrNew(['course_id' => $model->id, 'course_type' => 2, 'tab_edit' => 'edit']);
            $course_edit->course_id = $model->id;
            $course_edit->tab_edit = 'edit';
            $course_edit->course_type = 2;
            $course_edit->save();

            /************** create class defaul************/
            $exsits =OfflineCourseClass::where(['course_id'=>$model->id,'default'=>1])->exists();
            if (!$exsits){
                OfflineCourseClass::firstOrCreate(['course_id'=>$model->id,'default'=>1],[
                    'name' => 'Lớp mặc định',
                    'default' =>1,
                    'students'=>$model->max_student,
                    'code'=> $model->code.'_001',
                    'start_date'=>$model->start_date,
                    'end_date'=>$model->end_date
                ]);
            }
            /********update thống kê khóa học **********/
            if (empty($request->id))
                CourseStatistic::update_course_insert_statistic($model->id,2);
            else
                CourseStatistic::update_course_update_statistic($model->id,2,$date_original);
            /*********************end***********************/
            /*update khóa học kỳ thi */
            if ($request->id){
                //Quiz::where('course_id','=',$model->id)->where('course_type','=',2)->update(['course_id'=>0,'course_type'=>0, 'quiz_type_by_offline' => null]);

                $history_edit = new OnlineHistoryEdit();
                $history_edit->course_id = $model->id;
                $history_edit->user_id = profile()->user_id;
                $history_edit->tab_edit = 'Sửa thông tin khóa học';
                $history_edit->ip_address = \request()->ip();
                $history_edit->type = 2;
                $history_edit->save();
            }else{
                $history_edit = new OnlineHistoryEdit();
                $history_edit->course_id = $model->id;
                $history_edit->user_id = profile()->user_id;
                $history_edit->tab_edit = 'Thêm thông tin khóa học';
                $history_edit->ip_address = \request()->ip();
                $history_edit->type = 2;
                $history_edit->save();
            }

            // if ($request->quiz_id){
            //     Quiz::where('id','=',$request->quiz_id)->update(['course_id'=>$model->id,'course_type'=>2, 'quiz_type_by_offline' => 'quiz_id']);
            // }

            // if($request->entrance_quiz_id){
            //     Quiz::where('id','=',$request->entrance_quiz_id)->update(['course_id'=>$model->id,'course_type'=>2, 'quiz_type_by_offline' => 'entrance_quiz_id']);
            // }

            /**************************/
            $redirect = route('module.offline.edit', ['id' => $model->id]);

            $resgiters = OfflineRegister::where('course_id', '=', $model->id)
                ->where('status', '=', 1)
                ->get();
            foreach ($resgiters as $resgiter){
                Indemnify::where('user_id', '=', $resgiter->user_id)
                    ->where('course_id', '=',  $model->id)
                    ->update([
                        'coefficient' => $model->coefficient,
                    ]);

                $indem = Indemnify::checkExists($resgiter->user_id, $model->id);
                if ($indem && $indem->commit_amount){
                    $indem->commit_amount = ($indem->course_cost * $indem->coefficient) + $indem->cost_student;
                    $indem->save();
                }
            }

            $report_11 = ReportNewExportBC11::query()->where('course_id', '=', $model->id)->where('course_type', '=', 2);
            if ($request->id && $report_11->exists()){
                $training_form = TrainingType::query()->find($model->training_type_id);
                $training_location = TrainingLocation::query()->find($model->training_location_id);
                $subject = Subject::query()->find($model->subject_id);
                $course_time = preg_replace("/[^0-9]/", '', $model->course_time);
                $total_register = OfflineRegister::whereCourseId($model->id)->count();

                $report_11->update([
                    'course_code' => @$model->code,
                    'course_name' => @$model->name,
                    'subject_id' => @$subject->id,
                    'subject_name' => @$subject->name,
                    'training_form_id' => @$training_form->id,
                    'training_form_name' => @$training_form->name,
                    'course_time' => $course_time,
                    'start_date' => @$model->start_date,
                    'end_date' => @$model->end_date,
                    'total_register' => $total_register,
                    'training_location_id' => @$training_location->id,
                    'training_location_name' => @$training_location->name,
                ]);
            }

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

            /*Lưu mẫu đánh giá tổ chức giảng dạy ra theo khoá học*/
            $offline_teaching_organization_template = OfflineTeachingOrganizationTemplate::where('course_id', $model->id);
            if($model->template_rating_teacher_id && !$offline_teaching_organization_template->exists()){
                $template = RatingTemplate::find($model->template_rating_teacher_id)->toArray();

                $new_template = new OfflineTeachingOrganizationTemplate();
                $new_template->fill($template);
                $new_template->id = $template['id'];
                $new_template->course_id = $model->id;
                $new_template->save();

                $categories = RatingCategory::query()->where('template_id', $template['id'])->get()->toArray();
                foreach ($categories as $category){
                    $new_category = new OfflineTeachingOrganizationCategory();
                    $new_category->fill($category);
                    $new_category->id = $category['id'];
                    $new_category->course_id = $model->id;
                    $new_category->save();

                    $questions = RatingQuestion::query()->where('category_id', $category['id'])->get()->toArray();
                    foreach ($questions as $question){
                        $new_question = new OfflineTeachingOrganizationQuestion();
                        $new_question->fill($question);
                        $new_question->id = $question['id'];
                        $new_question->course_id = $model->id;
                        $new_question->save();

                        $answers = RatingQuestionAnswer::query()->where('question_id', $question['id'])->get()->toArray();
                        foreach ($answers as $answer){
                            $new_answer = new OfflineTeachingOrganizationAnswer();
                            $new_answer->fill($answer);
                            $new_answer->id = $answer['id'];
                            $new_answer->course_id = $model->id;
                            $new_answer->save();
                        }

                        $answers_matrix = RatingAnswerMatrix::query()->where('question_id', $question['id'])->get()->toArray();
                        foreach ($answers_matrix as $answer_matrix){
                            $new_answer_matrix = new OfflineTeachingOrganizationAnswerMatrix();
                            $new_answer_matrix->fill($answer_matrix);
                            $new_answer_matrix->course_id = $model->id;
                            $new_answer_matrix->save();
                        }
                    }
                }
            }
            /*****************************************************/

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => $redirect,
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    //LƯU GIẢNG VIÊN
    public function saveTeacher($user_ids, $course_id) {
        OfflineTeacher::where('course_id', $course_id)->delete();
        foreach($user_ids as $user_id) {
            $training_teacher = TrainingTeacher::whereUserId($user_id)->first();
            if(!$training_teacher){
                $user = Profile::where('user_id', '=', $user_id)->first();

                $training_teacher = new TrainingTeacher();
                $training_teacher->user_id = $user_id;
                $training_teacher->code = $user->code;
                $training_teacher->name = $user->lastname . ' ' . $user->firstname;
                $training_teacher->phone = $user->phone;
                $training_teacher->email = $user->email;
                $training_teacher->save();
            }

            $saveTeacher = new OfflineTeacher();
            $saveTeacher->teacher_id = $training_teacher->id;
            $saveTeacher->course_id = $course_id;
            $saveTeacher->save();
        }
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $id){
            $course = OfflineCourse::find($id);
            $result = OfflineResult::where('course_id', '=', $id);
            if ($result->exists() || $course->status == 1){
                continue;
            }

            ReportNewExportBC11::query()
                ->where('course_id', '=', $id)
                ->where('course_type', '=', 2)
                ->delete();
            ReportNewExportBC08::query()
                ->where('course_id', '=', $id)
                ->delete();
            ReportNewExportBC05::query()
                ->where('course_id', '=', $id)
                ->where('course_type', '=', 2)
                ->delete();

            CourseStatistic::update_course_delete_statistic(2, $course->start_date);

            Quiz::where('course_id','=', $id)
                ->where('course_type', '=', 2)
                ->update(['course_id'=>0,'course_type'=>0]);

            if($course->delete()){
                $offlineCourse = OfflineCourse::find($id);
                $data = OfflineRegister::select('id','user_id','course_id')->with('user:user_id,code,firstname,lastname,gender,email')->where(['course_id'=>$id,'status'=>1])->get();
                foreach ($data as $item) {
                    $signature = getMailSignature($item->user_id);
                    $params = [
                        'gender' => $item->user->gender=='1'?'Anh':'Chị',
                        'full_name' => $item->user->full_name,
                        'firstname' => $item->user->firstname,
                        'course_code' => $offlineCourse->code,
                        'course_name' => $offlineCourse->name,
                        'signature' => $signature
                    ];
                    $user_id = [$item->user_id];
                    $this->saveEmailDeletedCourse($params,$user_id,$item->id);
                }
            }
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function ajaxGetSubject(Request $request){
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
        ], $request, [
            'training_program_id' => 'Chuong trinh dao tao',
        ]);

        $training_program_id = $request->training_program_id;
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
        $check_user_invited = OfflineInviteRegister::query()->where('user_id', '=', profile()->user_id);
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
                OfflineCourse::where('id', $id)->update(['isopen' => $status]);
                OfflineCourseView::where('id', $id)->update(['isopen' => $status]);
                CourseView::where(['course_id' => $id, 'course_type' => 2])->update(['isopen' => $status]);
            }
        } else {
            OfflineCourse::where('id', $ids)->update(['isopen' => $status]);
            OfflineCourseView::where('id', $ids)->update(['isopen' => $status]);
            CourseView::where(['course_id' => $ids, 'course_type' => 2])->update(['isopen' => $status]);
        }

        json_result([
            'status' => 'success',
            'message' =>trans('laother.successful_save'),
        ]);
    }

    public function saveObject($course_id, Request $request){
        // $this->validateRequest([
        //     'type' => 'required|in:1,2',
        // ], $request, OfflineObject::getAttributeName());

        $check_all_title = $request->check_all_title;
        $object_type = $request->object_type;
        $title_level = $request->title_rank_id;
        $titles = $request->title ? explode(',', $request->title) : [];
        $units = explode(',', $request->unit_id);
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

                    if (OfflineObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->whereNull('title_id')->exists()) {
                        continue;
                    }

                    if (OfflineObject::where('course_id', '=', $course_id)->where('unit_id', '=', $unit->id)->where('title_id', '=', $item)->exists()) {
                        continue;
                    }

                    if (OfflineObject::where('course_id', '=', $course_id)->whereNull('unit_id')->where('title_id', '=', $item)->exists()) {
                        OfflineObject::where('course_id', '=', $course_id)
                            ->whereNull('unit_id')
                            ->where('title_id', '=', $item)
                            ->update([
                                'unit_id' => $unit->id,
                                'unit_level' => $unit->level,
                            ]);
                    }else{
                        $model = new OfflineObject();
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

                if (OfflineObject::where('course_id', '=', $course_id)
                    ->where('unit_id', '=', $item)
                    ->exists()) {
                    continue;
                }

                if (!Unit::where('id', '=', $item)->exists()) {
                    continue;
                }

                $unit = Unit::find($item);

                $model = new OfflineObject();
                $model->unit_id = $item;
                $model->unit_level = $unit->level;
                $model->type = $type;
                $model->course_id = $course_id;
                $model->created_by = profile()->user_id;
                $model->updated_by = profile()->user_id;
                $model->fill($request->all());
                $model->save();
            }
        }

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Thêm đối tượng tham gia khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'object']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'object';
        $course_edit->course_type = 2;
        $course_edit->save();

        return \response()->json([
            'status' => 'success',
            'message' => 'Thêm đối tượng thành công',
        ]);
    }

    public function getObject($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineObject::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
            'c.name AS unit_name',
            'd.name AS unit_manager',
        ]);
        $query->from('el_offline_object AS a')
            ->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id')
            ->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id')
            ->leftJoin('el_unit AS d', 'd.code', '=', 'c.parent_code')
            ->where('a.course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        return \response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function removeObject($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        OfflineObject::destroy($item);

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Xoá đối tượng tham gia khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveCost($course_id, Request $request){
        $find = [',', ';', '.'];

        $cost_ids = $request->id;
        $plan_amounts = str_replace($find, '', $request->plan_amount);
        $actual_amounts = str_replace($find, '', $request->actual_amount);
        $notes = $request->note;
        // dd($cost_ids);
        foreach($cost_ids as $key => $cost_id){
            if (empty($cost_id) || $cost_id <= 0) {
                continue;
            }
            $model = OfflineCourseCost::firstOrNew(['course_id' => $course_id, 'cost_id' => $cost_id]);
            $model->cost_id = $cost_id;
            $model->plan_amount = $plan_amounts[$key] ? $plan_amounts[$key] : 0;
            $model->actual_amount = $actual_amounts[$key] ? $actual_amounts[$key] : 0;
            $model->notes = $notes[$key] ? $notes[$key] : '';
            $model->course_id = $course_id;
            $model->save();
        }
        /*****update sum chi phí khóa học ***/
        $cost_class = OfflineCourseCost::sumActualAmount($course_id);
        $resgiters = OfflineRegister::where('course_id', '=', $course_id)
            ->where('status', '=', 1)
            ->get();

        if (count($resgiters) > 0){
            $course_cost = $cost_class/$resgiters->count();

            $course = OfflineCourse::find($course_id);

            foreach ($resgiters as $resgiter){
                Indemnify::where('user_id', '=', $resgiter->user_id)
                    ->where('course_id', '=', $course_id)
                    ->update([
                        'course_cost' => $course_cost,
                    ]);

                $indem = Indemnify::checkExists($resgiter->user_id, $course->id);

                if ($indem && $indem->commit_amount){
                    $indem->commit_amount = ($indem->course_cost * $indem->coefficient) + $indem->cost_student;
                    $commit_date = CommitMonth::getMonth($indem->commit_amount);
                    $indem->commit_date = $commit_date * 30;
                    $indem->save();
                }
            }
        }

        OfflineCourse::where('id', '=', $course_id)
            ->update([
                'cost_class' => $cost_class
            ]);
//        OfflineCourseView::where('id',$course_id)->update(['cost_class' => $cost_class]);
        /******update course view******/
        $this->updateCostCourseView($course_id);
        /**************/
        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Thêm chi phí đào tạo';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'cost']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'cost';
        $course_edit->course_type = 2;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Thêm chi phí đào tạo thành công',
        ]);
    }

    private function updateCostCourseView($course_id){
        $data = OfflineCourseCost::where('course_id',$course_id)->select(DB::raw('sum(plan_amount) as plan_amount, sum(actual_amount) as actual_amount'))->first();
        OfflineCourseView::where(['id'=>$course_id])->update(['plan_amount'=>$data->plan_amount,'actual_amount'=>$data->actual_amount]);
        CourseView::where(['course_id'=>$course_id,'course_type'=>2])->update(['plan_amount'=>$data->plan_amount,'actual_amount'=>$data->actual_amount]);
    }

    public function saveStudentCost($course_id, Request $request){
        $register_id = $request->regid;
        $cost_ids = $request->cost_id;
        $costs = str_replace(',','',$request->cost);
        $notes = $request->note;

        foreach($cost_ids as $key => $cost_id){

            if (empty($cost_id) || $cost_id <= 0) {
                continue;
            }

            if(OfflineStudentCost::checkExists($register_id, $cost_id)){
                OfflineStudentCost::where('register_id', '=', $register_id)
                ->where('cost_id', '=', $cost_id)
                ->update([
                    'cost' => (float) $costs[$key],
                    'note' => $notes[$key],
                ]);
                continue;
            }

            $model = new OfflineStudentCost();
            $model->cost_id = $cost_id;
            $model->cost = (float) $costs[$key];
            $model->note = $notes[$key];
            $model->register_id = $register_id;

            $model->save();
        }
        /**update chi phí học viên vào table cam kết bồi hoàn*/
        $cost_studet = OfflineStudentCost::getTotalStudentCost($register_id);
        $user_id = OfflineRegister::find($register_id)->user_id;

        if(Indemnify::checkExists($user_id, $course_id)){
            Indemnify::where('user_id', '=', $user_id)
                ->where('course_id', '=', $course_id)
                ->update([
                    'cost_student' => $cost_studet,
                ]);
            $indem = Indemnify::checkExists($user_id, $course_id);
            if ($indem && $indem->commit_amount){
                $indem->commit_amount = ($indem->course_cost * $indem->coefficient) + $indem->cost_student;
                $commit_date = CommitMonth::getMonth($indem->commit_amount);
                $indem->commit_date = $commit_date * 30;
                $indem->save();
            }

        }else{
            $model = new Indemnify();
            $model->cost_student = $cost_studet;
            $model->course_id = $course_id;
            $model->user_id = $user_id;
            $model->save();
        }
        /**********/

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Thêm chi phí học viên';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();


        json_result([
            'status' => 'success',
            'message' => 'Thêm chi phí học viên thành công',
        ]);
    }

    private function saveIndemnify($course_id){
        $totalUser = OfflineRegister::where(['course_id'=>$course_id,'status'=>1])->count("id");
        $users = OfflineRegisterView::leftJoin('el_titles','el_titles.id','=','el_offline_register_view.title_id')
            ->where(['course_id'=>$course_id,'el_offline_register_view.status'=>1])
            ->whereNotExists(function (Builder $subquery){
            $subquery->select('user_id')->from('el_indemnify')
                ->whereColumn('user_id','=','el_offline_register_view.user_id')
                ->whereColumn('course_id','=','el_offline_register_view.course_id');
        })
            ->get(['el_offline_register_view.id','el_offline_register_view.user_id','el_offline_register_view.title_id','el_titles.group']);

        $totalAmount = OfflineStudentCost::getTotalActualAmount($course_id);
        $commit_amount = $course_cost = $totalUser>0?$totalAmount/$totalUser:0;
        foreach($users as $key => $user){

            $dayCommit = CommitMonth::getDayCommit($user->group,$course_cost);
            Indemnify::updateOrCreate(['user_id' => $user->user_id,'course_id' => $course_id],
                [
                    'user_id' => $user->user_id,
                    'course_id' => $course_id,
                    'commit_date' => $dayCommit? $dayCommit: null,
                    'course_cost' => $course_cost,
                    'exemption_amount' => 0,
                    'coefficient' => 1,
                    'commit_amount' => $commit_amount,
                ]);
        }
    }

    public function saveCommitDate($course_id, Request $request){
        $register_id = $request->id;
        $user_id = $request->user_id;
        $course_cost = str_replace(',','', $request->course_cost);
        $commitDate = $request->commit_date;
        $coefficient = $request->coefficient;
        $calculator = $request->calculator;
        $exemption_amount = str_replace(',', '', $request->exemption_amount);
        $month = $request->month;
        foreach($register_id as $key => $value){
            $indem = Indemnify::checkExists($user_id[$key], $course_id);
            $title_id = Profile::find($user_id[$key])->title_id;
            $titleRank = Titles::find($title_id)->group;
            if ($calculator[$key]=='-' && ((double) $exemption_amount[$key] > ($course_cost[$key] * $coefficient[$key] + ($indem->cost_student ? $indem->cost_student : 0))) )
            {
                json_message('Số tiền miễn giảm không thể lớn hơn số tiền cam kết', 'error');
            }

            if (empty($value) || $value <= 0) {
                continue;
            }

            if($indem){
                Indemnify::updateOrCreate(['user_id' => $user_id[$key],'course_id' => $course_id],
                [
                    'user_id' => $user_id[$key],
                    'course_id' => $course_id,
                    'exemption_amount' => (double)$exemption_amount[$key],
                    'course_cost' => $course_cost[$key],
                    'coefficient' => $coefficient[$key],
                    'calculator' => $exemption_amount[$key]>0? $calculator[$key]:null,
                ]);
                try {
                    $model = Indemnify::checkExists($user_id[$key], $course_id);
                    if ($model->calculator == '+') {
                        $model->commit_amount = (float)$model->course_cost * 1 + (float)$model->cost_student + (float)$model->exemption_amount;
                    } elseif ($model->calculator == '-')
                        $model->commit_amount = (float)$model->course_cost * 1 + (float)$model->cost_student - (float)$model->exemption_amount;
                    else
                        $model->commit_amount = ((float)$model->course_cost * 1) + (float)$model->cost_student;
                    $commit_date = CommitMonth::getDayCommit($titleRank,$model->commit_amount);
                    $model->commit_date = $commitDate[$key]>0?$commitDate[$key]: $commit_date;
                    $model->save();
                }catch (\Exception $e){
                    dd($e,$model->calculator, $model->course_cost,$model->cost_student);
                }
                continue;
            }

//            $model = new Indemnify();
//            $model->commit_date = (int) $commit_date[$key] ? $commit_date[$key] : ($month ? $month : null);
//            $model->exemption_amount = $exemption_amount[$key];
//            $model->course_id = $course_id;
//            $model->course_cost = $course_cost[$key];
//            $model->coefficient = $coefficient[$key];
//            $model->user_id = $user_id[$key];
//            $model->commit_amount = $course_cost[$key] * $coefficient[$key];
//            $model->calculator = $exemption_amount[$key]>0? $calculator[$key]:null;
//            $model->save();
        }

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Thêm số tháng cam kết';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'cost_student']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'cost_student';
        $course_edit->course_type = 2;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.update_successful'),
        ]);
    }

    public function getModalStudentCost($course_id, Request $request) {
        $this->validateRequest([
            'regid' => 'required',
        ], $request);
        $get_commit_amount = str_replace(',','',$request->get_commit_amount);
        $model = OfflineCourse::find($course_id);
        $student_costs = StudentCost::where('status','=',1)->get(['id','name']);
        $register = OfflineStudentCost::getRegister($request->regid);
        $register_cost = OfflineStudentCost::where('register_id', '=', $register->id)->get();
        $get_total_student_cost = OfflineStudentCost::getTotalStudentCost($register->id);
        $total_student_cost = !empty($get_total_student_cost) ? ($get_total_student_cost + $get_commit_amount) : 0;
        // dd(ceil($total_student_cost));
        return view('offline::modal.student_cost', [
            'model' => $model,
            'course_id' => $course_id,
            'regid' => $request->regid,
            'student_costs' => $student_costs,
            'register' => $register,
            'register_cost' => $register_cost,
            'total_student_cost' => $total_student_cost
        ]);
    }

    public function saveCondition($course_id, Request $request){
        $this->validateRequest([
            'ratio' => 'nullable|numeric|min:1|max:100',
            'minscore' => 'nullable|numeric|min:1',
        ], $request, OfflineCondition::getAttributeName());

        $offlineCondition = OfflineCondition::firstOrNew(['course_id' => $course_id]);
        $offlineCondition->fill($request->all());
        $offlineCondition->course_id = $course_id;
        $offlineCondition->ratio = $request->input('ratio');
        $offlineCondition->minscore = $request->input('minscore');
        $offlineCondition->survey = $request->survey;
        $offlineCondition->certificate = $request->certificate;
        $offlineCondition->save();

        if(OfflineCondition::checkExists($course_id)){
            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = profile()->user_id;
            $history_edit->tab_edit = 'Cập nhật điều kiện hoàn thành khóa học';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 2;
            $history_edit->save();

        }else{
            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = profile()->user_id;
            $history_edit->tab_edit = 'Thêm điều kiện hoàn thành khóa học';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 2;
            $history_edit->save();
        }

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'condition']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'condition';
        $course_edit->course_type = 2;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function saveScheduleParent($course_id, Request $request){
        $this->validateRequest([
            'start_time' => 'required',
            'end_time' => 'required',
            'lesson_date' => 'required|date_format:d/m/Y',
        ], $request, OfflineScheduleParent::getAttributeName());

        $id = $request->id;
        $lesson_date = date_convert($request->input('lesson_date'));
        $start_time = $request->input('start_time') . ':00';
        $end_time = $request->input('end_time') . ':00';

        $check = OfflineCourse::where('id', '=', $course_id)
                ->where('start_date', '>', date_convert($request->lesson_date, $start_time))
                ->where('end_date', '<', date_convert($request->lesson_date, $end_time))
                ->exists();
        if ($check){
            json_message('Lịch học không nằm trong thời gian học', 'error');
        }

        $check_exist1 = OfflineScheduleParent::where('id', '!=', $id)
            ->where('lesson_date', '=', $lesson_date)
            ->where('start_time', '<=', $start_time)
            ->where('end_time', '>=', $start_time)
            ->where('course_id', '=', $course_id)
            ->exists();

        if ($check_exist1){
            json_message('Giờ học đã tồn tại', 'error');
        }

        $check_exist2 = OfflineScheduleParent::where('id', '!=', $id)
            ->where('lesson_date', '=', $lesson_date)
            ->where('start_time', '<=', $end_time)
            ->where('end_time', '>=', $end_time)
            ->where('course_id', '=', $course_id)
            ->exists();

        if ($check_exist2){
            json_message('Giờ học đã tồn tại', 'error');
        }

        if(get_date($start_time, 'H') >= get_date($end_time, 'H') && get_date($start_time, 'i') >= get_date($end_time, 'i')){
            json_message('Giờ kết thúc phải sau Giờ bắt đầu', 'error');
        }

        $model = OfflineScheduleParent::firstOrNew(['id' => $id]);
        $model->start_time = $start_time;
        $model->end_time = $end_time;
        $model->lesson_date = $lesson_date;
        $model->course_id = $course_id;
        if (empty($id)){
            $model->created_by = profile()->user_id;
        }
        $model->updated_by = profile()->user_id;

        if ($model->save()) {

            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = profile()->user_id;
            $history_edit->tab_edit = empty($id) ? 'Thêm lịch học' : 'Sửa lịch học';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 2;
            $history_edit->save();

            json_result([
                'status' => 'success',
                'message' => 'Thêm thành công',
            ]);
        }
    }

    public function getScheduleParent($course_id, Request $request){
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineScheduleParent::query();
        $query->select(['a.*']);
        $query->from('el_offline_schedule_parent AS a');
        $query->where('a.course_id', '=', $course_id);

        $count = $query->count();
        $query->orderBy('a.lesson_date', 'ASC');
        $query->orderBy('a.start_time', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->start_time = get_date($row->start_time, 'H:i');
            $row->end_time = get_date($row->end_time, 'H:i');
            $row->lesson_date = get_date($row->lesson_date, 'd/m/Y');
            $row->created_by = Profile::fullname($row->created_by);
            $row->updated_by = Profile::fullname($row->updated_by);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeScheduleParent($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request);

        $item = $request->input('ids');
        foreach ($item as $value){
            OfflineAttendance::whereCourseId($course_id)
                ->whereIn('schedule_id', function ($sub) use ($course_id, $value){
                    $sub->select(['id'])
                        ->from('el_offline_schedule')
                        ->where('course_id', '=', $course_id)
                        ->where('schedule_parent_id', '=', $value)
                        ->pluck('id')
                        ->toArray();
                })->delete();

            OfflineSchedule::query()
                ->where('course_id', '=', $course_id)
                ->where('schedule_parent_id', '=', $value)
                ->delete();
        }

        OfflineScheduleParent::destroy($item);

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Xoá lịch học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function getModalSchedule($course_id, Request $request) {
        $schedule_parent_id = $request->schedule_parent_id;
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $lesson_date = $request->lesson_date;
        $model = OfflineCourse::find($course_id);
        $teachers_offline = OfflineSchedule::getTeacher($course_id);

        return view('offline::backend.offline.form.schedule', [
            'model' => $model,
            'course_id' => $course_id,
            'schedule_parent_id' => $schedule_parent_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'lesson_date' => $lesson_date,
            'teachers_offline' => $teachers_offline,
        ]);
    }

    public function saveSchedule($course_id, Request $request){
        $this->validateRequest([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'teacher_id' => 'required|exists:el_training_teacher,id',
            'cost_teacher' => 'nullable|min:0',
            'teacher_type' => 'nullable|min:0',
        ], $request, OfflineSchedule::getAttributeName());

        $schedule_parent_id = $request->schedule_parent_id;
        $teacher_id = $request->teacher_id;
        $teacher_type = $request->teacher_type;
        $cost_teacher = $request->cost_teacher;
        $lesson_date = date_convert($request->lesson_date);
        $start_time = $request->start_time . ':00';
        $end_time = $request->end_time . ':00';

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $hours = $end->diffInHours($start);

        $schedule_parent = OfflineScheduleParent::find($schedule_parent_id);

        if($teacher_type == 1) {
            $schedule_teachers = OfflineSchedule::where('lesson_date', $lesson_date)->where('teacher_main_id', $teacher_id)->get(['start_time','end_time','lesson_date','course_id']);
        } else {
            $schedule_teachers = OfflineSchedule::where('lesson_date', $lesson_date)->where('teach_id', $teacher_id)->get(['start_time','end_time','lesson_date','course_id']);
        }

        foreach ($schedule_teachers as $key => $item) {
            if($item->end_time >= $start_time) {
                $name_course = OfflineCourse::where('id', $item->course_id)->first(['name']);
                json_message('Giờ dạy bị trùng với khóa học '. $name_course->name, 'error');
                break;
            }
        }

        if (get_date($start_time,'H:i') < get_date($schedule_parent->start_time, 'H:i')){
            json_message('Giờ học không được trước Giờ bắt đầu', 'error');
        }

        if (get_date($end_time, 'H:i') > get_date($schedule_parent->end_time, 'H:i')){
            json_message('Giờ học không được sau Giờ kết thúc', 'error');
        }

        if(get_date($start_time, 'H:i') >= get_date($end_time, 'H:i') ){
            json_message('Giờ kết thúc phải sau Giờ bắt đầu', 'error');
        }

        // $check_exist1 = OfflineSchedule::where('schedule_parent_id', '=', $schedule_parent_id)
        //     ->where('start_time', '<=', $start_time)
        //     ->where('end_time', '>=', $start_time)
        //     ->where('course_id', '=', $course_id)
        //     ->exists();

        // if ($check_exist1){
        //     json_message('Giờ học đã tồn tại', 'error');
        // }

        // $check_exist2 = OfflineSchedule::where('schedule_parent_id', '=', $schedule_parent_id)
        //     ->where('start_time', '<=', $end_time)
        //     ->where('end_time', '>=', $end_time)
        //     ->where('course_id', '=', $course_id)
        //     ->exists();

        // if ($check_exist2){
        //     json_message('Giờ học đã tồn tại', 'error');
        // }

        $model = new OfflineSchedule();
        $model->schedule_parent_id = $schedule_parent_id;
        $model->start_time = $start_time;
        $model->end_time = $end_time;
        $model->lesson_date = $lesson_date;
        $model->teacher_main_id = ($teacher_type == 1 ? $teacher_id : null);
        $model->teach_id = ($teacher_type == 2 ? $teacher_id : null);
        $model->cost_teacher_main = ($teacher_type == 1 ? ((int)$cost_teacher * (int)$hours) : null);
        $model->cost_teach_type = ($teacher_type == 2 ? ((int)$cost_teacher * (int)$hours) : null);
        $model->total_lessons = 1;
        $model->course_id = $course_id;

        if ($model->save()) {
            $this->updateScheduleCourseView($course_id);
            $history_edit = new OnlineHistoryEdit();
            $history_edit->course_id = $course_id;
            $history_edit->user_id = profile()->user_id;
            $history_edit->tab_edit = 'Thêm lịch học';
            $history_edit->ip_address = \request()->ip();
            $history_edit->type = 2;
            $history_edit->save();

            $this->updateReportNewBC11($model);

            json_result([
                'status' => 'success',
                'message' => 'Thêm thành công',
            ]);
        }
    }

    private function updateScheduleCourseView($course_id){
        $schedules = OfflineSchedule::where('course_id',$course_id)->select('total_lessons','start_time','end_time','lesson_date')->get();
        $strSchedule='';
        foreach ($schedules as $index => $schedule) {
            $strSchedule.= 'Buổi '.$schedule->total_lessons.' ('. get_date($schedule->start_time,'H:i').' '.get_date($schedule->end_time,'H:i').' - '.get_date($schedule->lesson_date, 'd/m/Y').')'.PHP_EOL;
        }
        OfflineCourseView::where(['id'=>$course_id])->update(['schedules'=>$strSchedule]);
        CourseView::where(['course_id'=>$course_id,'course_type'=>2])->update(['schedules'=>$strSchedule]);
    }

    public function getSchedule($course_id, Request $request){
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $schedule_parent_id = $request->schedule_parent_id;

        $query = OfflineSchedule::query();
        $query->select(['a.*', 'b.name as main_name', 'c.name as teach_name']);
        $query->from('el_offline_schedule AS a');
        $query->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_main_id');
        $query->leftJoin('el_training_teacher AS c', 'c.id', '=', 'a.teach_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.schedule_parent_id', '=', $schedule_parent_id);

        $count = $query->count();
        $query->orderBy('a.lesson_date', 'ASC');
        $query->orderBy('a.start_time', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->start_time = get_date($row->start_time, 'H:i');
            $row->end_time = get_date($row->end_time, 'H:i');
            if ($row->main_name){
                $row->teacher_name = $row->main_name;
                $cost_teacher = $row->cost_teacher_main;
                $row->teacher_type = 'Giảng viên chính';
            }else{
                $row->teacher_name = $row->teach_name;
                $cost_teacher = $row->cost_teach_type;
                $row->teacher_type = 'Trợ giảng';
            }
            $row->cost_teacher = number_format($cost_teacher, 0). ' VNĐ';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeSchedule($course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request);

        $item = $request->input('ids');

        OfflineAttendance::whereCourseId($course_id)->whereIn('schedule_id', $item)->delete();

        OfflineSchedule::destroy($item);

        ReportNewExportBC11::query()->whereIn('schedule_id', $item)->delete();

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Xoá lịch học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();
        $this->updateScheduleCourseView($course_id);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function ajaxGetCourseCode(Request $request){
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

        $subject_id = $request->subject_id;
        $id = $request->id;
        $subject = Subject::find($subject_id);
        $courses = OfflineCourse::where('subject_id', '=', $subject->id)->get();
        $level_subject = LevelSubject::find($subject->level_subject_id);
        $training_program = TrainingProgram::find($subject->training_program_id);

        $count_course = count($courses);
        $check_count_course = '';
        for ($i = 1; $i <= $count_course; $i++) {
            $count = '00'.$i;
            $get_course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
            $check_subject_course_code = OfflineCourse::where('code',$get_course_code)->first();
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
            $get_course_code_subject = OfflineCourse::find($id);
            if($get_course_code_subject->subject_id == $subject_id ) {
                $course_code = $get_course_code_subject->code;
            }
        }

        $color = Config::where('name','color_offline')->first();
        $i_text = Config::where('name','i_text_offline')->first();
        $b_text = Config::where('name','b_text_offline')->first();

        json_result([
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

    public function filterLocation(Request $request)
    {
        if ($request->province_id){
            $district = District::query()->where('province_id','=',$request->province_id)->get();
            echo json_result($district);
        }
        exit();
    }

    public function filterTrainingLocation(Request $request)
    {
        $query = TrainingLocation::query();
        $query->where('province_id', '=', $request->province_id);
        $query->where('district_id', '=', $request->district_id);
        $query->where('status', 1);
        $trainingLocation = $query->get();
        json_result($trainingLocation);
    }

    public function approve(Request $request) {
        $user_invited = null;
        $check_user_invited = OfflineInviteRegister::query()->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = $check_user_invited->pluck('course_id')->toArray();
        }

        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        foreach ($ids as $id) {
            if ($user_invited && in_array($id, $user_invited)){
                continue;
            }
            (new ApprovedModelTracking())->updateApprovedTracking($id,$status);

//            $model = OfflineCourse::findOrFail($id);
//            $model->status = $status;
//            $model->lock_course= 1;
//            $model->save();
            $this->updateEmailCourseObject($id);
        }

        if($status == 0) {
            json_message('Đã từ chối','success');
        } else {
            json_message('Duyệt thành công','success');
        }
    }

    public function lockCourse(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);
        $user_invited = null;
        $check_user_invited = OfflineInviteRegister::query()->where('user_id', '=', profile()->user_id);
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
                $model = OfflineCourse::findOrFail($id);
                $model->lock_course = $status;
                $model->save();
            }
        } else {
            $model = OfflineCourse::findOrFail($ids);
            $model->lock_course = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function sendMailApprove(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, ['ids' => trans('lamenu.course')]);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $course = OfflineCourse::find($id);
            $users = [];
            if ($course->status != 1) {
                $automail = new Automail();
                $automail->template_code = 'approve_course';
                $automail->params = [
                    'code' => $course->code,
                    'name' => $course->name,
                    'start_date' => $course->start_date,
                    'end_date' => $course->end_date
                ];
                $automail->users = $users;
                $automail->check_exists = true;
                $automail->check_exists_status = 0;
                $automail->object_id = $course->id;
                $automail->object_type = 'approve_offline';
                $automail->addToAutomail();
            }
        }

        json_message('Gửi mail thành công');
    }
    public function updateEmailCourseObject($course_id)
    {
//        $data = OfflineCourseComplete::with('users:id,user_id,email,firstname,lastname,gender')->where('course_id',$course_id)->get()->pluck('users')->flatten();
//        dd($data->toArray());
//        return false;
        $course = OfflineCourse::find($course_id);
        // theo đơn vị
        $objects_unit = OfflineObject::select('id','course_id','unit_id','title_id')->where('course_id',$course_id)
            ->with('unit:id,code',
                'unit.profiles:unit_code,id,code,user_id,email,firstname,lastname,gender')->has('unit')->get();
        foreach ($objects_unit as $object) {
            foreach ($object->unit['profiles'] as $profile) {
                $signature = getMailSignature($profile->user_id);
                $params = [
                    'gender' => $profile->gender=='1'?'Anh':'Chị',
                    'full_name' => $profile->full_name,
                    'firstname' => $profile->firstname,
                    'course_code' => $course->code,
                    'course_name' => $course->name,
                    'course_type' => 'Offline',
                    'start_date' => get_date($course->start_date),
                    'end_date' => get_date($course->end_date),
                    'training_location' => 'Elearning',
                    'url' => route('module.offline.detail', ['id' => $course->id]),
                    'signature' => $signature
                ];
                $user_id = [$profile->user_id];
                $this->saveEmailCourseObject($params,$user_id,$course->id);
            }
        }
        //theo chức danh
        $objects = OfflineObject::select('id','course_id','unit_id','title_id')->where('course_id',$course_id)
            ->with('titles:id,code',
                'titles.profiles:title_code,id,code,user_id,email,firstname,lastname,gender')->whereNotNull('title_id')->get();
        foreach ($objects as $object) {
            foreach ($object->titles as $profiles) {
                foreach ($profiles->profiles as $profile){
                    $signature = getMailSignature($profile->user_id);
                    $params = [
                        'gender' => $profile->gender=='1'?'Anh':'Chị',
                        'full_name' => $profile->full_name,
                        'firstname' => $profile->firstname,
                        'course_code' => $course->code,
                        'course_name' => $course->name,
                        'course_type' => 'Offline',
                        'start_date' => get_date($course->start_date),
                        'end_date' => get_date($course->end_date),
                        'training_location' => 'Elearning',
                        'url' => route('module.offline.detail', ['id' => $course->id]),
                        'signature' => $signature
                    ];
                    $user_id = [$profile->user_id];
                    $this->saveEmailCourseObject($params,$user_id,$course->id);
                }
            }
        }
    }

    public function saveEmailCourseObject(array $params,array $user_id,int $course_id)
    {
        $automail = new Automail();
        $automail->template_code = 'register_course_object';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $course_id;
        $automail->object_type = 'register_course_offline_object';
        $automail->addToAutomail();
    }
    public function sendMailChange(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, ['ids' => trans('lamenu.course')]);

        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $course = OfflineCourse::find($id);
            $users = OfflineRegister::where('course_id', '=', $id)
                ->where('status', '=', 1)
                ->pluck('user_id')
                ->toArray();

            $automail = new Automail();
            $automail->template_code = 'course_change';
            $automail->params = [
                'code' => $course->code,
                'name' => $course->name,
                'start_date' => $course->start_date,
                'end_date' => $course->end_date,
                'url' => route('module.offline.detail', ['id' => $id])
            ];
            $automail->users = $users;
            $automail->check_exists = true;
            $automail->check_exists_status = 0;
            $automail->object_id = $course->id;
            $automail->object_type = 'course_offline_change';
            $automail->addToAutomail();
        }

        json_message('Gửi mail thành công');
    }

    public function getDataHistory($course_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OnlineHistoryEdit::query();
        $query->select([
            'a.*',
            'b.code',
            'b.firstname',
            'b.lastname',
        ]);
        $query->from('el_online_history_edit AS a');
        $query->leftJoin('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('type', '=', 2);

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->fullname = $row->lastname . ' ' . $row->firstname . ' (' . $row->code . ')';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function saveEmailDeletedCourse(array $params,array $user_id,int $register_id)
    {
        $automail = new Automail();
        $automail->template_code = 'delete_course';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $register_id;
        $automail->object_type = 'delete_course_offline';
        $automail->addToAutomail();
    }

    public function uploadfile(Request $request) {
        $this->validate($request, [
            'filenames' => "required|string"
        ]);

        $course_id = $request->course_id;
        if ($request->filenames) {
            $model = new OfflineCourseUpload();
            $model->upload = path_upload($request->filenames);
            $model->course_id = $course_id;
            $model->save();
        } else {
            json_result([
                'status' => 'error',
                'message' => 'Chưa chọn file',
                'redirect' => route('module.offline.edit_upload', ['id' => $course_id])
            ]);
        }
        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'upload']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'upload';
        $course_edit->course_type = 2;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Đã tải lên thư vện file',
            'redirect' => route('module.offline.edit_upload', ['id' => $course_id])
        ]);
    }
    //Thư viên file
    public function getDataLibraryFile($course_id,Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineCourseUpload::query();
        $query->select('*');
        $query->from('el_offline_course_upload as a');
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
    public function removeLibraryFile(Request $request) {
        $ids = $request->input('ids', null);
        OfflineCourseUpload::destroy($ids);
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
            $page_child[$item->id] = route('module.offline.get_tree_child', ['id' => $course_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }
    public function getTreeChild($course_id, Request $request){
        $parent_code = $request->parent_code;
        return view('offline::backend.offline.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    private function updateReportNewBC11($model){
        $course = OfflineCourse::query()->find($model->course_id);
        $training_form = TrainingType::query()->find($course->training_type_id);
        $training_location = TrainingLocation::query()->find($course->training_location_id);
        $subject = Subject::query()->find($course->subject_id);
        $course_time = $course->course_time;
        $total_register = OfflineRegister::whereCourseId($course->id)->count();

        $start = Carbon::parse($model->start_time);
        $end = Carbon::parse($model->end_time);
        $hours = $end->diffInHours($start);

        if ($model->end_time <= '12:00:00'){
            $time_schedule = 'Sáng '. get_date($model->lesson_date);
        }else{
            $time_schedule = 'Chiều '. get_date($model->lesson_date);
        }

        $cost_lecturer = $model->cost_teacher_main * $model->total_lessons;
        $cost_tuteurs = $model->cost_teach_type ? ($model->cost_teach_type * $model->total_lessons) : null;

        $training_teacher = TrainingTeacher::query()->whereIn('id', [$model->teacher_main_id, $model->teach_id])->get();
        foreach ($training_teacher as $item){
            $title = '';
            $unit_1 = '';
            $unit_2 = '';
            $unit_3 = '';
            if ($item->type == 1){
                $profile = Profile::query()->find($item->user_id);
                $title = @$profile->titles;
                $unit_1 = @$profile->unit;
                $unit_2 = @$unit_1->parent;
                $unit_3 = @$unit_2->parent;
            }

            ReportNewExportBC11::query()->create([
                'training_teacher_id' => $item->id,
                'schedule_id' => $model->id,
                'user_id' => $item->user_id,
                'user_code' => $item->code,
                'fullname' => $item->name,
                'account_number' => $item->account_number,
                'role_lecturer' => ($item->id == $model->teacher_main_id) ? 1 : 0,
                'role_tuteurs' => ($item->id == $model->teach_id) ? 1 : 0,
                'unit_id_1' => @$unit_1->id,
                'unit_code_1' => @$unit_1->code,
                'unit_name_1' => @$unit_1->name,
                'unit_id_2' => @$unit_2->id,
                'unit_code_2' => @$unit_2->code,
                'unit_name_2' => @$unit_2->name,
                'unit_id_3' => @$unit_3->id,
                'unit_code_3' => @$unit_3->code,
                'unit_name_3' => @$unit_3->name,
                'position_name' => null,
                'title_id' => @$title->id,
                'title_code' => @$title->code,
                'title_name' => @$title->name,
                'course_id' => @$course->id,
                'course_code' => @$course->code,
                'course_name' => @$course->name,
                'course_type' => 2,
                'subject_id' => @$subject->id,
                'subject_name' => @$subject->name,
                'training_form_id' => @$training_form->id,
                'training_form_name' => @$training_form->name,
                'course_time' => $course_time,
                'time_lecturer' => ($item->id == $model->teacher_main_id) ? $hours : null,
                'time_tuteurs' => ($item->id == $model->teach_id) ? $hours : null,
                'start_date' => @$course->start_date,
                'end_date' => @$course->end_date,
                'time_schedule' => $time_schedule,
                'training_location_id' => @$training_location->id,
                'training_location_name' => @$training_location->name,
                'total_register' => $total_register,
                'cost_lecturer' => ($item->id == $model->teacher_main_id) ? $cost_lecturer : null,
                'cost_tuteurs' => ($item->id == $model->teach_id) ? $cost_tuteurs : null,
            ]);
        }
    }

    // SAO CHÉP KHOÁ HỌC
    public function copy(Request $request)
    {
        $ids = $request->input('ids', null);
        $user_role = UserRole::query()
            ->from('el_user_role as a')
            ->leftJoin('el_roles as b', 'b.id', '=', 'a.role_id')
            ->where('a.user_id', '=', profile()->user_id)
            ->first('b.code');

        foreach ($ids as $id) {
            $getCourse = OfflineCourse::findOrFail($id)->toArray();

            $courses = OfflineCourse::where('subject_id', '=', $getCourse['subject_id'])->get();
            $subject = Subject::find($getCourse['subject_id']);
            $count_course = count($courses);
            $check_count_course = '';
            for ($i = 1; $i <= $count_course; $i++) {
                $count = '00'.$i;
                $course_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
                $check_subject_course_code = OfflineCourse::where('code',$course_code)->first();
                if(empty($check_subject_course_code)) {
                    $check_count_course = $count;
                    break;
                }
            }
            $saveCourse = new OfflineCourse();
            $saveCourse->fill($getCourse);
            if( !empty($check_count_course) ) {
                $saveCourse->code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$check_count_course;
            } else {
                $count_course = count($courses) + 1;
                $count = '00'.$count_course;
                $saveCourse->code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count;
            }
            $saveCourse->isopen = 0;
            $saveCourse->status = 2;
            $saveCourse->lock_course = 0;
            $saveCourse->approved_step = '';
            $saveCourse->created_by = profile()->user_id;
            $saveCourse->updated_by = profile()->user_id;
            $saveCourse->unit_by = session()->get('user_unit');
            $saveCourse->save();
            CourseStatistic::update_course_insert_statistic($saveCourse->id,2);
        }

        json_message(trans('laother.copy_success'));
    }

    // ĐÁNH GIÁ KHÓA HỌC
    public function saveRattingCourse($id, Request $request) {
        $model = RattingCourse::firstOrNew(['course_id' => $id, 'type' => 2]);
        $model->course_id = $id;
        $model->teacher = $request->teacher;
        $model->program_content = $request->program_content;
        $model->organization = $request->organization;
        $model->quality_course = $request->quality_course;
        $model->type = 2;
        $model->save();

        return response()->json([
            'message' => trans('backend.save_success'),
            'status' => 'success'
        ]);
    }

    public function modalInfo($id, Request $request){
        $offline = OfflineCourse::find($id);
        $created_at2 = get_date($offline->created_at, 'H:i d/m/Y');

        $created_by = $offline->created_by ? $offline->created_by : 2;
        $updated_by = $offline->updated_by ? $offline->updated_by : 2;
        $user_created = ProfileView::where('user_id', $created_by)->first();
        $user_updated = ProfileView::where('user_id', $updated_by)->first();

        return view('offline::modal.modal_info', [
            'created_at2' => $created_at2,
            'user_created' => $user_created,
            'user_updated' => $user_updated,
        ]);
    }

    public function modalClass($id, Request $request){
        $obj = $request->obj;
        $register_class = OfflineCourseClass::where('course_id', $id)->get();

        return view('offline::modal.modal_register_class', [
            'register_class' => $register_class,
            'course_id' => $id,
            'obj' => $obj,
        ]);
    }

    //Thiết lập tham gia khóa học
    public function getSettingJoin($course_id, Request $request){
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = SettingJoinOfflineCourse::query();
        $query->select([
            'a.*',
        ]);
        $query->from('el_setting_join_offline_course AS a');
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
            $courses = OfflineCourse::whereIn('id', $course_complete_id)->get(['code', 'name']);

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
                SettingJoinOfflineCourse::updateOrCreate([
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
                SettingJoinOfflineCourse::updateOrCreate([
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
        $history_edit->type = 2;
        $history_edit->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'setting_join']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'setting_join';
        $course_edit->course_type = 2;
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
        SettingJoinOfflineCourse::destroy($item);

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Xóa thiết lập tham gia khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function changeDateRegister($course_id, Request $request){
        $setting_join_id = $request->setting_join;
        $date_register = $request->date_register;

        $model = SettingJoinOfflineCourse::find($setting_join_id);
        $model->date_register = $date_register;
        $model->save();

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $course_id;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Thay đổi số ngày Thiết lập tham gia khóa học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

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

        $redirect = route('module.offline.edit_setting_join', ['id' => $id]);

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình import ghi danh tự động',
            'redirect' => $redirect,
        ]);
    }

    public function runCronSettingJoin($course_id, Request $request) {
        \Artisan::call('command:setting_join_offline '. $course_id);

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình chạy ghi danh tự động',
        ]);
    }
    /******************************************************************************/

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

        $redirect = route('module.offline.management');

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình import',
            'redirect' => $redirect,
        ]);
    }

    // Import kết quả HV VÀO NHIỀU KHOÁ HỌC
    public function importResultMultipleCourse(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ResultMultiCourseImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.offline.management');

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình import',
            'redirect' => $redirect,
        ]);
    }

    //Cập nhật lại HV để chạy cron kết quả đào tạo
    public function updateResultByCondition($course_id, Request $request){
        $exists = OfflineRegister::where(['course_id'=>$course_id])->exists();
        if ($exists){
            OfflineRegister::where(['course_id'=>$course_id])
                ->whereNotNull('cron_complete')
                ->update([
                    'cron_complete' => 0
                ]);

            OfflineRegisterView::where(['course_id'=>$course_id])
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

        $save = SubjectPrerequisiteCourse::firstOrNew(['course_id' => $id, 'course_type' => 2]);
        $save->course_id = $id;
        $save->course_type = 2;
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
        $save = OfflineCourse::find($id);
        $save->survey_register = $request->survey_register;
        $save->register_quiz_id = $request->register_quiz_id;
        $save->save();

        $offline_course_view = OfflineCourseView::where(['id' => $id])->update([
            'survey_register' => $request->survey_register,
            'register_quiz_id' => $request->register_quiz_id,
        ]);

        $course_view = CourseView::where(['course_id' => $id, 'course_type' => 2])->update([
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

        $model = OfflineCourseDocument::firstOrNew(['id' => $request->id]);
        $model->name = $request->name;
        $model->document = path_upload($request->document);
        $model->course_id = $course_id;
        $model->save();

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'document']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'document';
        $course_edit->course_type = 2;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => 'Lưu thành công',
            'redirect' => route('module.offline.edit_document', ['id' => $course_id])
        ]);
    }
    public function getDataDocument($course_id, Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineCourseDocument::query();
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

        OfflineCourseDocument::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
