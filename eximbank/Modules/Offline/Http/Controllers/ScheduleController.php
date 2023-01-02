<?php


namespace Modules\Offline\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Automail;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TrainingTeacherHistory;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\CourseView;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\UserRole;
use App\Traits\TeamsMeetingTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseActivity;
use Modules\Offline\Entities\OfflineCourseActivityTeams;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineCourseView;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineScheduleParent;
use Modules\Online\Entities\OnlineHistoryEdit;
use Modules\Quiz\Entities\QuizPart;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use Modules\User\Entities\TrainingProcess;
use Modules\Offline\Entities\OfflineAttendance;
use App\Models\Categories\Province;
use App\Models\Categories\District;
use Modules\Offline\Imports\ScheduleImport;
use Modules\Offline\Exports\ScheduleTemplate;
use App\Models\PreviewImport;
use Modules\Offline\Entities\OfflineTeacherClass;
use Modules\Offline\Entities\OfflineNewTeacher;
use Modules\Offline\Entities\OfflineResult;
use App\Models\Categories\TrainingTeacherStar;
use Modules\Offline\Entities\OfflineCourseActivityCondition;
use Modules\Offline\Entities\OfflineActivityQuiz;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;

class ScheduleController extends Controller
{
    use TeamsMeetingTrait;
    public function index($course_id, $class_id, Request $request)
    {
        $course = OfflineCourse::find($course_id, ['id', 'name', 'lock_course', 'start_date', 'end_date']);
        $class = OfflineCourseClass::findOrFail($class_id);
        $anotherClass = OfflineCourseClass::where(['course_id'=>$course_id])->where('id','<>',$class_id)->get();
        $classArray = [];
        foreach ($anotherClass as $item) {
            $classArray[]=["name"=>$item->name,"url"=> route("module.offline.schedule",['id'=>$course_id,'class_id'=>$item->id])];
        }
        if ($request->ajax()){
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);

            $query = OfflineSchedule::query();
            $query->select(['a.*']);
            $query->from('el_offline_schedule AS a');
            $query->where('a.course_id', '=', $course_id);
            $query->where('a.class_id', '=', $class_id);

            $count = $query->count();
            $query->orderBy('a.lesson_date', 'ASC');
            $query->orderBy('a.start_time', 'ASC');
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();

            foreach ($rows as $row) {
                $row->edit = route('module.offline.edit_schedule', ['courseId' => $course_id, 'classId' => $class->id ,'id' => $row->id]);
                $row->start_time = get_date($row->start_time, 'H:i');
                $row->end_time = get_date($row->end_time, 'H:i');
                $row->lesson_date = get_date($row->lesson_date, 'd/m/Y');
                $row->end_date = get_date($row->end_date, 'd/m/Y');

                $row->created_by = Profile::fullname($row->created_by);
                $row->updated_by = Profile::fullname($row->updated_by);

                $start = Carbon::parse(get_date($row->start_time, 'H:i'));
                $end = Carbon::parse(get_date($row->end_time, 'H:i'));
                $hours = $end->diffInHours($start);

                $cost_teach_type = (int)$row->cost_teach_type * (int)$hours;
                $row->cost_teach_type = number_format($cost_teach_type, 0);

                $teacherName = [];

                $teacher = TrainingTeacher::find($row->teacher_main_id);
                $teacherName[] = $teacher->name;

                $teachers_main_other = OfflineNewTeacher::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $row->id])->pluck('new_teacher_id')->toArray();
                if(count($teachers_main_other) > 0){
                    foreach($teachers_main_other as $teacher_main_other){
                        $teacher = TrainingTeacher::find($teacher_main_other);
                        $teacherName[] = $teacher->name;
                    }
                }
                $row->teacherName = implode('; ', $teacherName);

                if(!empty($row->teach_id)) {
                    $allTeacher = [];
                    $tutor_id = explode(',', $row->teach_id);

                    foreach($tutor_id as $tutor) {
                        $teacher = TrainingTeacher::find($tutor);
                        $allTeacher[] = $teacher->name;
                    }
                    $row->tutorsName = $allTeacher;
                }
                $row->link_teams = '';
                if($row->type_study == 2){
                    $offline_activity_teams = OfflineCourseActivityTeams::where(['course_id' => $course_id,'class_id'=>$class_id,'schedule_id'=>$row->id])->first();
                    if($offline_activity_teams){
                        $row->link_teams = @$offline_activity_teams->join_url;
                    }

                    $row->url_report_teams = route('module.offline.activity.report_teams', ['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $row->id]);
                }

                $row->activity_url = '';
                if($row->type_study == 3){
                    $row->activity_url = route('module.offline.activity_by_schedule', ['course_id' => $course_id,'class_id'=>$class_id,'schedule_id'=>$row->id]);

                    $row->url_report_elearning = route('module.offline.activity.report_elearning', ['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $row->id]);
                }
            }
            json_result(['total' => $count, 'rows' => $rows]);
        }else {
            return view('offline::backend.schedule.index', [
                'course' => $course,
                'class' => $class,
                'classArray' => $classArray,
            ]);
        }
    }

    public function saveSchedule($courseId, $class_id, Request $request){
        $class = OfflineCourseClass::find($class_id);
        $type_study = $request->type_study;

        if($type_study == 3){
            $this->validateRequest([
                'type_study' => 'required',
                'end_date' => 'required_if:type_study,3',
                'lesson_date_3' => 'required_if:type_study,3',
                'start_time_3' => 'required_if:type_study,3|date_format:H:i',
                'end_time_3' => 'required_if:type_study,3|date_format:H:i',
            ], $request, OfflineSchedule::getAttributeName());
        }else{
            $this->validateRequest([
                'lesson_date' => 'required_if:type_study,1,2',
                'start_time' => 'required_if:type_study,1,2|date_format:H:i',
                'end_time' => 'required_if:type_study,1,2|date_format:H:i',
                'teacher_id' => 'required_if:type_study,1,2',
                'type_study' => 'required',
            ], $request, OfflineSchedule::getAttributeName());
        }

        $teacher_id = $request->teacher_id;

        if($type_study == 3){
            $end_date = date_convert($request->end_date);
            $lesson_date = date_convert($request->lesson_date_3);
            $start_time = $request->start_time_3 . ':00';
            $end_time = $request->end_time_3 . ':00';

            if($end_date == $lesson_date){
                if(get_date($start_time, 'H:i') >= get_date($end_time, 'H:i') ){
                    json_message('Giờ kết thúc phải sau Giờ bắt đầu', 'error');
                }
            }else if($end_date < $lesson_date){
                json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
            }

            $check_1 = OfflineSchedule::where('course_id', $courseId)
                ->where('class_id', $class_id)
                ->where('id', '!=', $request->id)
                ->where(\DB::raw("CONCAT(DATE(lesson_date), ' ', start_time)"), '<=', date_convert($request->lesson_date_3, $start_time))
                ->where(\DB::raw("CONCAT(DATE(end_date), ' ', end_time)"), '>=', date_convert($request->lesson_date_3, $start_time))
                ->exists();
            if($check_1){
                json_message('Ngày bắt đầu đã tồn tại trong Thời gian đào tạo', 'error');
            }
            $check_2 = OfflineSchedule::where('course_id', $courseId)
                ->where('class_id', $class_id)
                ->where('id', '!=', $request->id)
                ->where(\DB::raw("CONCAT(DATE(lesson_date), ' ', start_time)"), '<=', date_convert($request->end_date, $end_time))
                ->where(\DB::raw("CONCAT(DATE(end_date), ' ', end_time)"), '>=', date_convert($request->end_date, $end_time))
                ->exists();
            if($check_2){
                json_message('Ngày kết thúc đã tồn tại trong Thời gian đào tạo', 'error');
            }

            $check_3 = OfflineSchedule::where('course_id', $courseId)
                ->where('class_id', $class_id)
                ->where('id', '!=', $request->id)
                ->where('lesson_date', '>', $lesson_date)
                ->where('end_date', '<', $end_date)
                ->exists();
            if($check_3){
                json_message('Thời gian đào tạo đã tồn tại', 'error');
            }

            $check_4 = OfflineSchedule::where('course_id', $courseId)
                ->where('class_id', $class_id)
                ->where('id', '!=', $request->id)
                ->where('lesson_date', $lesson_date)
                ->where('start_time', '<=', $start_time)
                ->where('end_time', '>=', $start_time)
                ->exists();
            if($check_4){
                json_message('Giờ học đã tồn tại', 'error');
            }

            $check_5 = OfflineSchedule::where('course_id', $courseId)
                ->where('class_id', $class_id)
                ->where('id', '!=', $request->id)
                ->where('lesson_date', $end_date)
                ->where('start_time', '<=', $end_time)
                ->where('end_time', '>=', $end_time)
                ->exists();
            if($check_5){
                json_message('Giờ học đã tồn tại', 'error');
            }

            $check_6 = OfflineSchedule::where('course_id', $courseId)
                ->where('class_id', $class_id)
                ->where('id', '!=', $request->id)
                ->where(\DB::raw("CONCAT(DATE(lesson_date), ' ', start_time)"), '>=', date_convert($request->lesson_date_3, $start_time))
                ->where(\DB::raw("CONCAT(DATE(lesson_date), ' ', end_time)"), '<=', date_convert($request->end_date, $end_time))
                ->exists();
            if($check_6){
                json_message('Giờ học đã tồn tại', 'error');
            }
        }else{
            $lesson_date = get_date($request->lesson_date, 'Y-m-d');
            $start_time = $request->start_time . ':00';
            $end_time = $request->end_time . ':00';

            if(get_date($start_time, 'H:i') >= get_date($end_time, 'H:i') ){
                json_message('Giờ kết thúc phải sau Giờ bắt đầu', 'error');
            }

            $check_1 = OfflineSchedule::where('course_id', $courseId)
                ->where('class_id', $class_id)
                ->where('id', '!=', $request->id)
                ->where(\DB::raw("CONCAT(DATE(lesson_date), ' ', start_time)"), '<=', date_convert($request->lesson_date, $start_time))
                ->where(\DB::raw("CONCAT(DATE(end_date), ' ', end_time)"), '>=', date_convert($request->lesson_date, $end_time))
                ->exists();
            if($check_1){
                json_message('Ngày bắt đầu đã tồn tại trong Thời gian đào tạo', 'error');
            }

            // check giờ học trong lịch học
            $schedules = OfflineSchedule::where('course_id', $courseId)->where('class_id', $class_id)->where('lesson_date', $lesson_date)->get(['start_time','end_time']);
            if(!empty($schedules) && !$request->id) {
                foreach($schedules as $item) {
                    if (get_date($item->start_time, 'H:i') <= get_date($start_time,'H:i') && get_date($start_time, 'H:i') <= get_date($item->end_time, 'H:i')){
                        json_message('Giờ học đã tồn tại', 'error');
                    }

                    if (get_date($item->start_time, 'H:i') <= get_date($end_time,'H:i') && get_date($end_time, 'H:i') <= get_date($item->end_time, 'H:i')){
                        json_message('Giờ học đã tồn tại', 'error');
                    }

                    if (get_date($start_time,'H:i') <= get_date($item->start_time, 'H:i') && get_date($item->end_time, 'H:i') <= get_date($end_time, 'H:i')){
                        json_message('Giờ học đã tồn tại', 'error');
                    }
                }
            }
        }

        if($type_study != 3){ //check Gv lịch học khi loại không phải đào tạo elearning
            if ($request->condition_complete_teams && $request->condition_complete_teams > 100) {
                json_message(trans('latraining.percent_duration_attendance'). ' chỉ tối đa 100%', 'error');
            }

            // check giờ học trong các lớp học
            $schedulesAllClass = OfflineSchedule::where('course_id', $courseId)->where('class_id', '!=', $class_id)->where('lesson_date', $lesson_date)->get(['class_id', 'start_time','end_time', 'teacher_main_id']);
            if(!empty($schedulesAllClass) && !$request->id) {
                foreach($schedulesAllClass as $item) {
                    $class_name = OfflineCourseClass::find($item->class_id, ['name']);

                    if (get_date($item->start_time, 'H:i') <= get_date($start_time,'H:i') && get_date($start_time, 'H:i') <= get_date($item->end_time, 'H:i') && $teacher_id == $item->teacher_main_id){
                        json_message('Giảng viên đã đăng ký dạy lớp học: '. $class_name->name, 'error');
                    }

                    if (get_date($item->start_time, 'H:i') <= get_date($end_time,'H:i') && get_date($end_time, 'H:i') <= get_date($item->end_time, 'H:i') && $teacher_id == $item->teacher_main_id){
                        json_message('Giảng viên đã đăng ký dạy lớp học: '. $class_name->name, 'error');
                    }

                    if (get_date($start_time,'H:i') <= get_date($item->start_time, 'H:i') && get_date($item->end_time, 'H:i') <= get_date($end_time, 'H:i') && $teacher_id == $item->teacher_main_id){
                        json_message('Giảng viên đã đăng ký dạy lớp học: '. $class_name->name, 'error');
                    }
                }
            }

            // check giảng viên tồn tại giờ học trong các khóa học
            $scheduleAllCourses = OfflineSchedule::where('lesson_date', $lesson_date)->where('course_id', '!=', $courseId)->get(['course_id','start_time','end_time', 'teacher_main_id']);
            if(!empty($scheduleAllCourses)) {
                foreach($scheduleAllCourses as $value) {
                    if (get_date($value->start_time, 'H:i') <= get_date($start_time,'H:i') && get_date($start_time, 'H:i') <= get_date($value->end_time, 'H:i') && $teacher_id == $value->teacher_main_id){
                        $course = OfflineCourse::find($value->course_id, ['name']);
                        json_message('Giảng viên đã đăng ký dạy khóa học: '. $course->name, 'error');
                    }
                    if (get_date($value->start_time, 'H:i') <= get_date($end_time,'H:i') && get_date($end_time, 'H:i') <= get_date($value->end_time, 'H:i') && $teacher_id == $value->teacher_main_id){
                        $course = OfflineCourse::find($value->course_id, ['name']);
                        json_message('Giảng viên đã đăng ký giảng dạy khóa học: '. $course->name, 'error');
                    }

                    if (get_date($start_time,'H:i') <= get_date($value->start_time, 'H:i') && get_date($value->end_time, 'H:i') <= get_date($end_time, 'H:i') && $teacher_id == $item->teacher_main_id){
                        $course = OfflineCourse::find($value->course_id, ['name']);
                        json_message('Giảng viên đã đăng ký giảng dạy khóa học: '. $course->name, 'error');
                    }
                }
            }

            //Kiểm tra không có chọn trợ giảng nhưng có chi phí trợ giảng
            if(empty($request->tutors_id) && $request->cost_teach_type){
                json_message('Mời nhập trợ giảng', 'error');
            }
        }

        $find = [',', ';', '.'];
        $cost_teacher_main = str_replace($find, '', $request->cost_teacher_main);
        $cost_teach_type = str_replace($find, '', $request->cost_teach_type);

        if($request->id){
            $teacher_schedules = OfflineSchedule::where('id', $request->id)
            ->where('course_id', $courseId)
            ->where('class_id', $class_id)
            ->pluck('teacher_main_id')
            ->toArray();

            $check1 = array_diff($teacher_schedules, [$teacher_id]);//CŨ
        }

        $model = OfflineSchedule::firstOrNew(['id' => $request->id]);
        $model->start_time = $start_time;
        $model->end_time = $end_time;
        $model->lesson_date = $lesson_date;
        $model->end_date = $request->end_date ? $end_date : null;
        $model->teacher_main_id = $teacher_id;
        $model->teach_id = !empty($request->tutors_id) ? implode(',', $request->tutors_id) : null;
        $model->cost_teacher_main = (int)$cost_teacher_main;
        $model->cost_teach_type = (int)$cost_teach_type;
        $model->total_lessons = 1;
        $model->course_id = $courseId;
        $model->class_id = $class_id;
        $model->training_location_id = $class->training_location_id ? $class->training_location_id : null;
        $model->cost_by = $request->cost_by ? $request->cost_by : 1;
        $model->type_study = $request->type_study;
        $model->condition_complete_teams = $request->condition_complete_teams;
        $model->practical_teaching = $request->practical_teaching;

        // lưu teams
        if ($model->type_study == 2) {
            $invalid = $this->validateTeams($courseId, $class_id, $model->id, $lesson_date,$start_time,$end_time);
            if ($invalid)
                return json_message(trans('latraining.teams_message_error_update'), 'error');
        }

        if ($model->save()) {
            $this->updateScheduleSession($courseId, $class_id);
            if(!$request->id) {
                $this->updateScheduleCourseView($courseId);
                $history_edit = new OnlineHistoryEdit();
                $history_edit->course_id = $courseId;
                $history_edit->user_id = profile()->user_id;
                $history_edit->tab_edit = 'Thêm lịch học';
                $history_edit->ip_address = \request()->ip();
                $history_edit->type = 2;
                $history_edit->save();

                $this->updateReportNewBC11($model);
            }

            // lưu teams
            if ($model->type_study==2)
                $this->saveTeams($courseId, $class_id, $model->id);
            else
                $this->deleteActivityTeams($courseId, $class_id, $model->id);

            //Xoá GV không nằm trong lớp của khoá học khi có thay đổi GV dạy
            if(!empty($check1)) {
                foreach ($check1 as $key => $item) {
                    TrainingTeacherHistory::where(['course_id' => $courseId, 'class_id' => $class_id, 'schedule_id' => $model->id, 'teacher_id' => $item])->delete();
                    OfflineTeacherClass::where(['course_id' => $courseId, 'class_id' => $class_id, 'teacher_id' => $item])
                        ->whereNotIn('teacher_id', function($sub) use($courseId,  $class_id, $model){
                            $sub->select(['new_teacher_id'])
                                ->from('el_offline_new_teacher')
                                ->where(['course_id' => $courseId, 'class_id' => $class_id, 'schedule_id' => $model->id])
                                ->pluck('new_teacher_id')
                                ->toArray();
                        })
                        ->delete();
                }
            }

            //Lưu lịch sử giảng dạy GV
            $this->updateTrainingTeacherHistory($model);

            json_result([
                'status' => 'success',
                'message' => 'Thêm thành công',
                'redirect' => route('module.offline.edit_schedule', ['courseId' => $courseId, 'classId' => $class_id, 'id' => $model->id]),
            ]);
        }
    }
    private function validateTeams($course_id, $class_id, $schedule_id, $lesson_date,$start_time,$end_time){
        $schedule = OfflineSchedule::where('id', $schedule_id)->first();

        $lesson_date = $schedule? $schedule->lesson_date: $lesson_date;
        $start_time = $schedule? $schedule->start_time: $start_time;
        $end_time = $schedule? $schedule->end_time:$end_time;
        $start_time = get_date($lesson_date) .' '. $start_time;
        $end_time = get_date($lesson_date) .' '. $end_time;

        $start = Carbon::parse(get_date($start_time, 'H:i'));
        $end = Carbon::parse(get_date($end_time, 'H:i'));
        $duration = $end->diffInHours($start);

        $model = OfflineCourseActivityTeams::query()->where(['course_id' => $course_id,'class_id'=>$class_id,'schedule_id'=>(int)$schedule->id])->select('start_time')->first();
        if ($model) {
            if (time() >= strtotime($model->start_time))
                return true;
        }else{
            if (time() >= strtotime(get_date($start_time,'Y-m-d H:i:s')))
                return true;
        }
        return  false;
    }
    private function deleteActivityTeams($course_id, $class_id, $schedule_id){
        $activity = OfflineCourseActivityTeams::where(['course_id' => $course_id,'class_id'=>$class_id,'schedule_id'=>$schedule_id])->first();
        $teams_id = $activity->teams_id;
        $event_id = $activity->event_id;
        $startTime = $activity->start_time;
        if ($activity->exists) {
            if (time() >= strtotime($startTime)) {
                return json_message('Buổi học đã bắt đầu. Không thể xoá', 'error');
            }
            OfflineCourseActivityTeams::destroy($activity->id);
            $this->deleteTeamsMe($teams_id,$activity->user_teams_id);
//            $this->deleteEvent($event_id,$activity->user_teams_id);
        }
    }
    private function saveTeams($course_id, $class_id, $schedule_id)
    {
        $schedule = OfflineSchedule::where('id', $schedule_id)->first();
        $start_time = get_date($schedule->lesson_date) .' '. $schedule->start_time;
        $end_time = get_date($schedule->lesson_date) .' '. $schedule->end_time;

        $start = Carbon::parse(get_date($schedule->start_time, 'H:i'));
        $end = Carbon::parse(get_date($schedule->end_time, 'H:i'));
        $duration = $end->diffInHours($start);

        $model = OfflineCourseActivityTeams::firstOrNew(['course_id' => $course_id,'class_id'=>$class_id,'schedule_id'=>$schedule->id]);

        $topic = $model->exists ? $model->topic: 'Học Teams '.$start_time.' - '.$end_time;
        $data =[
            'subject' => $topic,
            'start_time' =>$start_time,
            'end_time' =>$end_time,
            'duration' =>$duration,
        ];
        if ($model->exists){
            $teams = $this->updateTeamsMe($model->teams_id,$model->user_teams_id, $data);
        }
        else
            $teams = $this->createTeamsMe($data);
        /*$joinUrl = $teams['data']->onlineMeeting->joinUrl;
        $joinWebUrl = $joinUrl;*/
        $model->topic = $topic;
        $model->start_time = datetime_convert($start_time);
        $model->end_time = datetime_convert($end_time);
        $model->duration = $duration;
        $model->course_id = $course_id;
        $model->class_id = $class_id;
        $model->schedule_id = $schedule_id;
        $model->join_url = $teams['data']->joinUrl;
        $model->join_web_url = $teams['data']->joinWebUrl;
        $model->meeting_code = $teams['data']->meetingCode;
        $model->teams_id = $teams['data']->id;
        $model->event_id = $teams['event_id'];
        $model->user_teams_id = $teams['user_teams_id'];

        if ($model->save()) {
            $offlineActivity = OfflineCourseActivity::firstOrNew(['course_id' => $course_id, 'activity_id' => 6, 'subject_id' => $model->id]);
            $offlineActivity->name = $topic;
            $offlineActivity->status = 1;
            $offlineActivity->lesson_id = 1;
            $offlineActivity->num_order = 1;
            $offlineActivity->save();
        }
    }

    // CHI TIẾT LỊCH HỌC
    public function form($courseId, $classId, $id = null) {
        $teacherTNT = OfflineTeacher::where(['course_id' => $courseId, 'tnt' => 1])->pluck('teacher_id')->toArray();
        $teacherTNT = implode(',', $teacherTNT);

        $course = OfflineCourse::find($courseId, ['id', 'name', 'lock_course', 'training_location_id', 'start_date', 'end_date']);
        $class = OfflineCourseClass::findOrFail($classId);

        $model = OfflineSchedule::firstOrNew(['id' => $id]);
        $province = Province::get(['code','name', 'id']);
        $classArray = [];
        $anotherClass = OfflineCourseClass::where(['course_id'=>$courseId])->where('id','<>',$classId)->get();
        foreach ($anotherClass as $item) {
            $classArray[]=["name"=>$item->name,"url"=> route("module.offline.schedule",['id'=>$courseId,'class_id'=>$item->id])];
        }

        $province_id = null;
        $district_id = null;
        $training_location_id = null;

        $user_invited = false;
        $check_user_invited = OfflineInviteRegister::query()
            ->where('course_id', '=', $courseId)
            ->where('user_id', '=', \Auth::id());
        if ($check_user_invited->exists()) {
            $user_invited = true;
        }

        if(empty($id)) {
            $teachers_offline = OfflineSchedule::getTeacher($courseId);
            $training_location_course = TrainingLocation::select('id', 'province_id', 'district_id')->where('id', $course->training_location_id)->first();
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
            return view('offline::backend.schedule.form', [
                'course' => $course,
                'model' => $model,
                'district' => $district,
                'training_location' => $training_location,
                'teachers_offline' => $teachers_offline,
                'province' => $province,
                'class' => $class,
                'classArray' => $classArray,
                'teacherTNT' => $teacherTNT,
                'province_id' => $province_id,
                'district_id' => $district_id,
                'training_location_id' => $training_location_id,
                'user_invited' => $user_invited,
            ]);
        } else {
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

            $newTeachers = OfflineNewTeacher::where(['course_id' => $courseId, 'class_id' => $classId, 'schedule_id' => $model->id])->get();
            $teacherIsset = $model->teacher_main_id;
            $new_teacher_id = $newTeachers->pluck('new_teacher_id')->toArray();
            $teachers_offline = OfflineSchedule::getTeacher($courseId, $new_teacher_id);
            $teachers_offline_new = OfflineSchedule::getTeacher($courseId, $teacherIsset);

            $model->lesson_date = get_date($model->lesson_date, 'd/m/Y');
            $model->end_date = get_date($model->end_date, 'd/m/Y');
            $model->cost_teacher_main = round($model->cost_teacher_main);
            $model->cost_teach_type = round($model->cost_teach_type);
            $model->start_time = get_date($model->start_time, 'H:i');
            $model->end_time = get_date($model->end_time, 'H:i');

            $teacherSchedule = [];
            if(!empty($model->teacher_main_id)) {
                $allTeacher = explode(',', $model->teacher_main_id);
                $teacherSchedule = $allTeacher;
            }
            $tutors_offline = OfflineSchedule::getTeacher($courseId, $teacherSchedule);
            $lockTeams = $model->type_study == 2 ? $this->validateTeams($courseId, $classId, $model->id, $model->lesson_date,$model->start_time,$model->end_time) : false;
            return view('offline::backend.schedule.form', [
                'course' => $course,
                'model' => $model,
                'district' => $district,
                'training_location' => $training_location,
                'teachers_offline' => $teachers_offline,
                'teachers_offline_new' => $teachers_offline_new,
                'province' => $province,
                'class' => $class,
                'classArray' => $classArray,
                'newTeachers' => $newTeachers,
                'teacherTNT' => $teacherTNT,
                'province_id' => $province_id,
                'district_id' => $district_id,
                'training_location_id' => $training_location_id,
                'lockTeams' => $lockTeams,
                'tutors_offline' => $tutors_offline,
                'user_invited' => $user_invited,
            ]);
        }
    }

    // XÓA LỊCH HỌC
    public function removeSchedule($courseId, $class_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request);

        $item = $request->input('ids');

        OfflineAttendance::whereCourseId($courseId)->whereIn('schedule_id', $item)->delete();
        foreach ($item as $value) {
            $offlineSchedule = OfflineSchedule::find($value);
            if ($offlineSchedule->type_study==2){
                $activityTeams = OfflineCourseActivityTeams::where(['course_id'=>$courseId,'class_id'=>$class_id,'schedule_id'=>$offlineSchedule->id])->first();
                OfflineCourseActivity::where(['course_id'=>$courseId,'activity_id'=>6,'subject_id'=>$activityTeams->id])->delete();
                $this->deleteActivityTeams($offlineSchedule->course_id,$offlineSchedule->class_id,$offlineSchedule->id);
            }else if ($offlineSchedule->type_study==3){
                OfflineCourseActivity::where(['course_id'=>$courseId,'schedule_id'=>$offlineSchedule->id])->delete();
                OfflineCourseActivityCondition::where(['course_id'=>$courseId,'schedule_id'=>$offlineSchedule->id])->delete();
            }

            OfflineSchedule::destroy($value);
        }
        ReportNewExportBC11::query()->whereIn('schedule_id', $item)->delete();

        $old_quiz = OfflineActivityQuiz::where(['course_id' => $courseId, 'class_id' => $class_id, 'schedule_id' => $item])->first();
        QuizRegister::where('quiz_id', $old_quiz->quiz_id)->delete();
        QuizResult::where('quiz_id', $old_quiz->quiz_id)->delete();
        $old_quiz->delete();

        $history_edit = new OnlineHistoryEdit();
        $history_edit->course_id = $courseId;
        $history_edit->user_id = profile()->user_id;
        $history_edit->tab_edit = 'Xoá lịch học';
        $history_edit->ip_address = \request()->ip();
        $history_edit->type = 2;
        $history_edit->save();

        $this->updateScheduleSession($courseId, $class_id);
        $this->updateScheduleCourseView($courseId);

        TrainingTeacherHistory::where('course_id', $courseId)->where('class_id', $class_id)->whereIn('schedule_id', $item)->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function changeTeacher($courseId, Request $request) {
        //Lấy danh sách GV còn lại làm trợ giảng
        $query = OfflineTeacher::query();
        $query->select([
            'a.*',
            'b.name',
        ]);
        $query->from('el_offline_course_teachers as a');
        $query->join('el_training_teacher as b', 'b.id', '=', 'a.teacher_id');
        $query->where('teacher_id', '!=', $request->teacherId);
        $query->where('course_id', $courseId);
        $teacher = $query->get();

        //Lấy chi phí GV chính
        $teacher_main = TrainingTeacher::find($request->teacherId);

        json_result([
            'teacher' => $teacher,
            'cost_teacher_main' => @$teacher_main->cost_teacher_main,
        ]);
    }

    public function changeTeacherTutors($courseId, Request $request) {
        //Lấy chi phí GV trợ giảng
        $teacher_main = TrainingTeacher::whereIn('id', $request->tutors_id)->first();

        json_result([
            'cost_teacher' => @$teacher_main->cost_teach_type,
        ]);
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

    private function updateReportNewBC11($model){
        $course = OfflineCourse::query()->find($model->course_id);
        $training_form = TrainingType::query()->find($course->training_type_id);
        $training_location = TrainingLocation::query()->find($model->training_location_id);
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

    private function updateTrainingTeacherHistory($model){
        $query = OfflineRegister::query();
        $query->where('course_id', $model->course_id);
        $query->where('class_id', $model->class_id);
        $query->where('status', 1);
        $num_student = $query->count();

        $num_user_rating = TrainingTeacherStar::where('teacher_id', $model->teacher_main_id)
            ->where('course_id', $model->course_id)
            ->where('course_type', 2)
            ->where('class_id', $model->class_id)
            ->count();
        $num_star = TrainingTeacherStar::where('teacher_id', $model->teacher_main_id)
            ->where('course_id', $model->course_id)
            ->where('course_type', 2)
            ->where('class_id', $model->class_id)
            ->sum('num_star');

        $num_star = (int)$num_star > 0 ? round($num_star/$num_user_rating, 1) : 0;

        $cost = 0;
        if($num_student >= 15 && $num_star >= 3.5) {
            $cost = $model->cost_teacher_main * $model->practical_teaching;
        }

        if($model->teacher_main_id){
            TrainingTeacherHistory::updateOrCreate([
                'course_id' => $model->course_id,
                'class_id' => $model->class_id,
                'schedule_id' => $model->id,
                'teacher_id' => $model->teacher_main_id,
                'teacher_type' => 1,
            ],[
                'course_id' => $model->course_id,
                'class_id' => $model->class_id,
                'schedule_id' => $model->id,
                'teacher_id' => $model->teacher_main_id,
                'teacher_type' => 1,
                'num_hour' => $model->practical_teaching ?? 0,
                'num_schedule' => 1,
                'cost' => $cost,
                'month' => get_date($model->lesson_date, 'm'),
                'year' => get_date($model->lesson_date, 'Y'),
            ]);

            //Lưu GV theo lớp
            OfflineTeacherClass::updateOrCreate([
                'class_id' => $model->class_id,
                'course_id' => $model->course_id,
                'teacher_id' => $model->teacher_main_id,
            ]);
        }

        //Lưu GV trợ giảng
        $start = Carbon::parse(get_date($model->start_time, 'H:i'));
        $end = Carbon::parse(get_date($model->end_time, 'H:i'));
        $num_hour = $end->diffInHours($start);

        if($model->teach_id){
            $teachers = explode(',', $model->teach_id);
            foreach($teachers as $teacher){
                TrainingTeacherHistory::updateOrCreate([
                    'course_id' => $model->course_id,
                    'class_id' => $model->class_id,
                    'schedule_id' => $model->id,
                    'teacher_id' => $teacher,
                    'teacher_type' => 2,
                ],[
                    'course_id' => $model->course_id,
                    'class_id' => $model->class_id,
                    'schedule_id' => $model->id,
                    'teacher_id' => $teacher,
                    'teacher_type' => 2,
                    'num_hour' => $num_hour,
                    'num_schedule' => 1,
                    'cost' => ($model->cost_teach_type * $num_hour),
                    'month' => get_date($model->lesson_date, 'm'),
                    'year' => get_date($model->lesson_date, 'Y'),
                ]);
            }
        }
    }

    public function trainingLocationSchedule($courseId, $class_id, Request $request) {
        $course = OfflineCourse::find($courseId, ['training_location_id']);
        $training_location_course = TrainingLocation::select('id', 'province_id', 'district_id')->where('id', $course->training_location_id)->first();

        if (!empty($training_location_course)) {
            $district = District::query()->where('province_id', '=', $training_location_course->province_id)->get(['id','name']);

            $training_location = TrainingLocation::where('province_id', '=', $training_location_course->province_id)
                ->where('district_id','=',$training_location_course->district_id)
                ->where('status','=',1)
                ->get(['id','name']);
        }else{
            $district = null;
            $training_location = null;
        }

        json_result([
            'training_location_course' => $training_location_course,
            'district' => $district,
            'training_location' => $training_location,
        ]);
    }

    // IMPORT LỊCH HỌC
    public function importSchedule($course_id, Request $request) {
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $unit = $request->input('unit');

        $import = new ScheduleImport($course_id);
        \Excel::import($import, $request->file('import_file'));

        json_result([
            'data' => $import->data,
            'total_success' => $import->success,
            'total_fail' => $import->fail,
        ]);
    }

    // EXPORT MẪU IMPORT
    public function exportTemplateSchedule($course_id) {
        return (new ScheduleTemplate($course_id))->download('mau_import_lich_hoc.xlsx');
    }

    // LƯU IMPORT
    public function saveImportSchedule($course_id, Request $request) {
        if($request->type == 0) {
            PreviewImport::where('name_import', 'schedule')->delete();
        } else {
            $previewImports = PreviewImport::where('name_import', 'schedule')->get();
            foreach ($previewImports as $import) {
                $model = new OfflineSchedule();
                $model->start_time = $import->column1;
                $model->end_time = $import->column2;
                $model->lesson_date = $import->column3;
                $model->teacher_main_id = $import->column4;
                $model->teach_id = $import->column5;
                $model->cost_teacher_main = $import->column6;
                $model->cost_teach_type = $import->column7;
                $model->total_lessons = $import->column8;
                $model->course_id = $import->column9;
                $model->class_id = $import->column10;
                $model->training_location_id = $import->column11;
                $model->save();

                $this->updateScheduleCourseView($course_id);
                $this->updateReportNewBC11($model);
                $this->updateTrainingTeacherHistory($model);
            }
            PreviewImport::where('name_import', 'schedule')->delete();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function updateScheduleSession($courseId, $class_id) {
        $allScheduleWithLessonDate = OfflineSchedule::where('course_id', $courseId)->where('class_id', $class_id)->orderBy('lesson_date', 'ASC')->orderBy('start_time', 'ASC')->get('id');
        foreach($allScheduleWithLessonDate as $key => $scheduleWithLessonDate) {
            OfflineSchedule::where('id', $scheduleWithLessonDate->id)->update(['session' => $key + 1]);
        }
    }

    // LƯU GIẢNG VIÊN MỚI
    public function saveNewTeacher($course_id, $class_id, $id, Request $request) {
        $check = [];
        $new_teacher_old = OfflineNewTeacher::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $id])->pluck('new_teacher_id')->toArray();
        $new_teacher_id = $request->new_teacher_id;
        $id_teacher = $request->id_teacher;

        $check1 = array_diff($new_teacher_old, $new_teacher_id);//CŨ
        if(!empty($check1)) {
            foreach ($check1 as $key => $item) {
                TrainingTeacherHistory::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $id, 'teacher_id' => $item])->delete();
                OfflineTeacherClass::where(['course_id' => $course_id, 'class_id' => $class_id, 'teacher_id' => $item])->delete();
            }
        }

        $find = [',', ';', '.'];
        foreach ($new_teacher_id as $key => $teacher) {
            if(in_array($teacher, $check)) {
                json_message('Giảng viên đã tồn tại', 'error');
            }
            $save = OfflineNewTeacher::firstOrNew(['id' => $id_teacher[$key]]);
            $save->new_teacher_id = $teacher;
            $save->course_id = $course_id;
            $save->class_id = $class_id;
            $save->schedule_id = $id;
            $save->cost_new_teacher = $request->cost_new_teacher[$key] ?  str_replace($find, '', $request->cost_new_teacher[$key]) : 0;
            $save->practical_teaching_new_teacher = $request->practical_teaching_new_teacher[$key] ? $request->practical_teaching_new_teacher[$key] : 0;
            $save->save();
            array_push($check, $teacher);

            //Lưu lịch sử giảng dạy GV
            $this->updateTrainingNewTeacherHistory($save);
        }

        json_result([
            'status' => 'success',
            'message' => 'Thêm thành công',
            'redirect' => route('module.offline.edit_schedule', ['courseId' => $course_id, 'classId' => $class_id, 'id' => $id]),
        ]);
    }

    public function updateTrainingNewTeacherHistory($model) {
        $query = OfflineRegister::query();
        $query->where('course_id', $model->course_id);
        $query->where('class_id', $model->class_id);
        $query->where('status', 1);
        $num_student = $query->count();

        $num_user_rating = TrainingTeacherStar::where('teacher_id', $model->new_teacher_id)
            ->where('course_id', $model->course_id)
            ->where('course_type', 2)
            ->where('class_id', $model->class_id)
            ->count();
        $num_star = TrainingTeacherStar::where('teacher_id', $model->new_teacher_id)
            ->where('course_id', $model->course_id)
            ->where('course_type', 2)
            ->where('class_id', $model->class_id)
            ->sum('num_star');

        $num_star = (int)$num_star > 0 ? round($num_star/$num_user_rating, 1) : 0;

        $cost = 0;
        if($num_student >= 15 && $num_star >= 3.5) {
            $cost = $model->cost_new_teacher * $model->practical_teaching_new_teacher;
        }

        $offlineSchedule = OfflineSchedule::find($model->schedule_id, ['lesson_date']);

        $save = TrainingTeacherHistory::firstOrNew(['teacher_id' => $model->new_teacher_id, 'course_id' => $model->course_id, 'schedule_id' => $model->schedule_id, 'class_id' => $model->class_id]);
        $save->course_id = $model->course_id;
        $save->class_id = $model->class_id;
        $save->schedule_id = $model->schedule_id;
        $save->teacher_id = $model->new_teacher_id;
        $save->teacher_type = 1;
        $save->num_hour = $model->practical_teaching_new_teacher ? $model->practical_teaching_new_teacher : 0;
        $save->cost = $cost;
        $save->num_schedule = 1;
        $save->month = get_date($offlineSchedule->lesson_date, 'm');
        $save->year = get_date($offlineSchedule->lesson_date, 'Y');
        $save->save();

        //Lưu GV theo lớp
        OfflineTeacherClass::updateOrCreate([
            'class_id' => $model->class_id,
            'course_id' => $model->course_id,
            'teacher_id' => $model->new_teacher_id,
        ]);
    }

    // XÓA GIẢNG VIÊN MỚI
    public function deleteNewTeacher($course_id, $class_id, $id, Request $request) {
        OfflineNewTeacher::where('id', $request->id)->delete();
        TrainingTeacherHistory::where(['course_id' => $course_id, 'class_id' => $class_id, 'schedule_id' => $id, 'teacher_id' => $request->teacherId])->delete();
        OfflineTeacherClass::where(['course_id' => $course_id, 'class_id' => $class_id, 'teacher_id' => $request->teacherId])->delete();

        json_result([
            'status' => 'success',
            'message' => 'Xóa thành công',
        ]);
    }
}
