<?php


namespace Modules\Offline\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Automail;
use App\Models\Categories\Subject;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\ProfileView;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineMonitoringStaff;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Quiz\Entities\QuizPart;
use Modules\User\Entities\TrainingProcess;
use App\Models\CourseTabEdit;
use App\Events\SaveTrainingProcessRegister;
use App\Events\SendMailRegister;
use \Modules\Offline\Entities\OfflineSchedule;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\District;

class ClassController extends Controller
{
    public function index($course_id, Request $request)
    {
        $allUser = ProfileView::where('status_id', 1)->get();
        $offline = OfflineCourse::findOrFail($course_id);
        if ($request->ajax()){
            $search = $request->input('search');
            $sort = $request->input('sort', 'id');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);

            $query = OfflineCourseClass::where('course_id', '=', $course_id);
            $count = $query->count();
            $query->orderBy($sort, 'asc');
            $query->offset($offset);
            $query->limit($limit);

            $rows = $query->get();
            foreach ($rows as $index => $row) {
                $row->start_date = get_date($row->start_date);
                $row->end_date = get_date($row->end_date);

                $row->register_url = route('module.offline.register',['id'=>$course_id,'class_id'=>$row->id]);
                $row->teacher_url = route('module.offline.teacher',['id'=>$course_id,'class_id'=>$row->id]);
                $row->schedule_url = route('module.offline.schedule',['id'=>$course_id,'class_id'=>$row->id]);

                $check_schedule = OfflineSchedule::where(['course_id' => $course_id, 'class_id' => $row->id])->first(['id']);
                if (isset($check_schedule)) {
                    $url_attendance = route('module.offline.attendance', ['id' => $course_id, 'class_id' => $row->id]) . '?schedule=' . $check_schedule->id;
                } else {
                    $url_attendance = route('module.offline.attendance', ['id' => $course_id, 'class_id' => $row->id]);
                }
                $row->attendance_url = $url_attendance;

                $row->result_url = route('module.offline.result',['id'=>$course_id,'class_id'=>$row->id]);
                $row->evaluate_url = route('module.offline.rating_level',['id'=>$course_id,'class_id'=>$row->id]);
            }
            json_result(['total' => $count, 'rows' => $rows]);
        }else {
            $province_id = null;
            $district_id = null;
            $training_location_id = null;
            $training_location_course = TrainingLocation::select('id', 'province_id', 'district_id')->where('id', $offline->training_location_id)->first();
            if (!empty($training_location_course)) {
                $districts = District::query()->where('province_id', '=', $training_location_course->province_id)->get(['id','name']);
                $training_location = TrainingLocation::where('province_id', '=', $training_location_course->province_id)
                    ->where('district_id','=',$training_location_course->district_id)
                    ->where('status','=',1)
                    ->get(['id','name','code']);

                $province_id = $training_location_course->province_id;
                $district_id = $training_location_course->district_id;
                $training_location_id = $training_location_course->id;
            }else{
                $districts = null;
                $training_location = null;
            }
            
            $quiz_part = function ($quiz_id) {
                return QuizPart::where('quiz_id', '=', $quiz_id)->get();
            };
            $user_invited = false;
            $check_user_invited = OfflineInviteRegister::query()
                ->where('course_id', '=', $course_id)
                ->where('user_id', '=', profile()->user_id);
            if ($check_user_invited->exists()) {
                $user_invited = true;
            }
            $class = OfflineCourseClass::where('default',1)->first();
            return view('offline::backend.class.index', [
                'offline' => $offline,
                'course_id' => $course_id,
                'quiz_part' => $quiz_part,
                'user_invited' => $user_invited,
                'class' => $class,
                'districts' => $districts,
                'training_location' => $training_location,
                'province_id' => $province_id,
                'district_id' => $district_id,
                'training_location_id' => $training_location_id,
            ]);
        }
    }

    public function registerClassList($course_id,$class_id,Request $request)
    {
        $errors = session()->get('errors');
        \Session::forget('errors');
        $class = OfflineCourseClass::findOrFail($class_id);
        $offline = OfflineCourse::findOrFail($course_id);

        $quiz_part = function ($quiz_id) {
            return QuizPart::where('quiz_id', '=', $quiz_id)->get();
        };
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $user_has_role_register = UserRole::query()
            ->whereIn('role_id', function ($sub){
                $sub->select(['a.role_id'])
                    ->from('el_role_has_permissions as a')
                    ->leftJoin('el_permissions as b', 'b.id', '=', 'a.permission_id')
                    ->whereIn('b.name', ['offline-course-register', 'offline-course-register-create'])
                    ->pluck('a.role_id')
                    ->toArray();
            })
            ->where('user_id', '!=', profile()->user_id)
            ->where('user_id', '>', 2)
            ->get();

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = true;
        }

        return view('offline::backend.register.index', [
            'offline' => $offline,
            'course_id' => $course_id,
            'quiz_part' => $quiz_part,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'user_has_role_register' => $user_has_role_register,
            'user_invited' => $user_invited,
            'class' => $class,
        ]);
    }

    public function registerClassCreate($course_id,$class_id,Request $request)
    {
        $max_unit = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };
        $class = OfflineCourseClass::findOrFail($class_id);
        $offline = OfflineCourse::findOrFail($course_id);
        return view('offline::backend.register.form', [
            'course_id' => $course_id,
            'offline' => $offline,
            'max_unit' => $max_unit,
            'level_name' => $level_name,
            'class'=>$class
        ]);
    }

    public function saveRegisterClass($course_id, $class_id, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, OfflineRegister::getAttributeName());

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $course_id)
            ->where('class_id', '=', $class_id)
            ->where('user_id', '=', profile()->user_id);
        if ($check_user_invited->exists()){
            $user_invited = true;
            $num_register = $check_user_invited->first()->num_register;
        }

        $ids = $request->input('ids', null);
        $course = OfflineCourse::findOrFail($course_id, ['id', 'code', 'name', 'start_date', 'end_date', 'cert_code', 'subject_id']);
        $subject = Subject::findOrFail($course->subject_id, ['id', 'code', 'name']);
        foreach($ids as $id){
            if ($user_invited){
                if ($num_register == 0){
                    continue;
                }else{
                    $num_register -= 1;

                    OfflineInviteRegister::query()
                        ->where('course_id', '=', $course_id)
                        ->where('class_id', '=', $class_id)
                        ->where('user_id', '=', profile()->user_id)
                        ->update([
                            'num_register' => $num_register
                        ]);
                }
            }

            if (OfflineRegister::checkExists($id,  $course_id, $class_id)) {
                continue;
            }
            $model = new OfflineRegister();
            $model->user_id = $id;
            $model->course_id = $course_id;
            $model->class_id = $class_id;
            if ($model->save()) {
                // update training process
                event(new SaveTrainingProcessRegister($course, $subject, $id, $class_id, 2));

                $users = UnitManager::getManagerOfUser($model->user_id);
                event(new SendMailRegister($users, $course, 2));
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    // CHỈNH SỬA LỚP
    public function editClass($courseId, Request $request) {
        $model = OfflineCourseClass::select()->where('course_id', $courseId)->where('id', $request->id)->first();
        $model->start_date = get_date($model->start_date, 'Y-m-d');
        $model->end_date = get_date($model->end_date, 'Y-m-d');

        $monitoringStaff = OfflineMonitoringStaff::where('course_id', $courseId)->where('class_id', $model->id)->get();
        if(!empty($monitoringStaff)) {
            foreach($monitoringStaff as $item) {
                $profile = ProfileView::where('user_id', $item->user_id)->first(['full_name','code']);
                $item->full_name = $profile->full_name;
                $item->code = $profile->code;
            }
        }

        $province_id = null;
        $district_id = null;
        $training_location_id = null;
        $training_location_course = TrainingLocation::select('id', 'province_id', 'district_id')->where('id', $model->training_location_id)->first();
        if (!empty($training_location_course)) {
            $district = District::query()->where('province_id', '=', $training_location_course->province_id)->get(['id','name']);
            $training_location = TrainingLocation::where('province_id', '=', $training_location_course->province_id)
                ->where('district_id','=',$training_location_course->district_id)
                ->where('status','=',1)
                ->get(['id','name','code']);

            $province_id = $training_location_course->province_id;
            $district_id = $training_location_course->district_id;
            $training_location_id = $training_location_course->id;
        }else{
            $district = null;
            $training_location = null;
        }

        json_result([
            'model' => $model,
            'monitoringStaff' => $monitoringStaff,
            'district' => $district,
            'training_location' => $training_location,
            'province_id' => $province_id,
            'district_id' => $district_id,
            'training_location_id' => $training_location_id,
        ]);
    }

    //LƯU LỚP
    public function saveClass($courseId, Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ], $request, OfflineCourseClass::getAttributeName());

        $checkClass = OfflineCourseClass::where('course_id', $courseId)->first();
        $saveClass = OfflineCourseClass::firstOrNew(['id' => $request->id]);
        $course = OfflineCourse::find($courseId, ['code','max_student']);

        // if((int)$request->students > (int)$course->max_student) {
        //     json_message('Số lượng học viên phải bé hơn hoặc bằng số học viên khóa học là: '. $course->max_student, 'error');
        // }

        if(($checkClass->id != $request->id) || empty($request->id)) {
            $countClass = OfflineCourseClass::where('course_id', $courseId)->count();
            $checkCountClass = '';
            for ($i = 1; $i <= $countClass; $i++) {
                if($i > 9) {
                    $count = '0' . $i;
                } else {
                    $count = '00' . $i;
                }
                $classCode = $course->code . '_' . $count;
                $checkExistsClass = OfflineCourseClass::where('code', $classCode)->exists();
                if(!$checkExistsClass) {
                    $checkCountClass = $count;
                    break;
                }
            }
            if(!empty($checkCountClass)) {
                $classCode = $course->code . '_' . $checkCountClass;
            } else {
                $totalClass = $countClass + 1;
                if($totalClass > 9) {
                    $count = '0' . $totalClass;
                } else {
                    $count = '00' . $totalClass;
                }
                $classCode = $course->code . '_' . $count;
            }
            $saveClass->code = $classCode;
        }
        $saveClass->course_id = $courseId;
        $saveClass->name = $request->name;
        $saveClass->start_date = get_date($request->start_date, 'Y-m-d');
        $saveClass->end_date = get_date($request->end_date, 'Y-m-d');
        $saveClass->students = $request->students;
        $saveClass->training_location_id = $request->training_location_id;
        $saveClass->save();

        if($saveClass->save()) {
            $course_edit = CourseTabEdit::firstOrNew(['course_id' => $courseId, 'course_type' => 2, 'tab_edit' => 'class']);
            $course_edit->course_id = $courseId;
            $course_edit->tab_edit = 'class';
            $course_edit->course_type = 2;
            $course_edit->save();
        }

        if(!empty($request->monitoring_staff)) {
            OfflineMonitoringStaff::where('course_id', $courseId)->where('class_id', $saveClass->id)->delete();
            foreach($request->monitoring_staff as $monitoring_staff) {
                $saveMonitoring = new OfflineMonitoringStaff();
                $saveMonitoring->course_id = $courseId;
                $saveMonitoring->class_id = $saveClass->id;
                $saveMonitoring->user_id = $monitoring_staff;
                $saveMonitoring->save();
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    // XÓA LỚP HỌC
    public function removeClass($courseId, Request $request) {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.classroom'),
        ]);

        $checkClass = OfflineCourseClass::where('course_id', $courseId)->first();
        $ids = $request->input('ids');
        if(in_array($checkClass->id, $ids)) {
            json_message('Không được xóa lớp học mặc định', 'error');
        }
        foreach($ids as $id) {
            OfflineCourseClass::find($id)->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
