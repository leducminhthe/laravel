<?php
namespace Modules\Offline\Imports;

use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineTeacher;
use App\Models\ProfileView;
use Modules\Offline\Entities\OfflineSchedule;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TrainingLocation;
use Modules\Online\Entities\OnlineHistoryEdit;
use Modules\Offline\Entities\OfflineCourseView;
use App\Models\CourseView;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Subject;
use Modules\Offline\Entities\OfflineRegister;
use Carbon\Carbon;
use App\Models\Profile;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use App\Models\Categories\TrainingTeacherHistory;
use App\Models\PreviewImport;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\User\Entities\TrainingProcess;

class ScheduleImport implements ToModel, WithStartRow
{
    public $data;
    public $success = 0;
    public $fail = 0;
    public $course_id;

    public function __construct($course_id)
    {
        $this->course_id = $course_id;
    }

    public function model(array $row)
    {
        $error = false;
        $errors = [];

        if (empty($row[1])){
            $errors[1] = '<strong class="text-danger"> Không được trống </strong>';
            $error = true;
        }

        if (empty(trim($row[2]))){
            $errors[2] = '<strong class="text-danger"> Không được trống </strong>';
            $error = true;
        }

        if (empty($row[3])){
            $errors[3] = '<strong class="text-danger"> Không được trống </strong>';
            $error = true;
        }

        if (empty($row[4])){
            $errors[4] = '<strong class="text-danger"> Không được trống </strong>';
            $error = true;
        }

        if (empty($row[5])){
            $errors[5] = '<strong class="text-danger"> Không được trống </strong>';
            $error = true;
        }

        if (isset($row[6])){
            $tutors = explode(',', $row[6]);
            foreach($tutors as $tutor) {
                if($tutor == $row[5]) {
                    $errors[6] = '<strong class="text-danger"> Không được phép là trợ giảng </strong>';
                    $error = true;
                }
            }
        }
        
        $offlineCourse = OfflineCourse::find($this->course_id, ['start_date','end_date','training_location_id']);
        $timeStart = get_date($row[3], 'H:i');
        $timeEnd = get_date($row[4], 'H:i');

        // check mã lớp học
        $checkCodeClass = OfflineCourseClass::where('course_id', $this->course_id)->where('code', trim($row[1]))->first(['id']);
        if (empty($checkCodeClass) && !$errors[1]){
            $errors[1] = '<strong class="text-danger"> Không tồn tại </strong>';
            $error = true;
        }

        // check ngày bắt đầu
        $startDate = get_date($row[2], 'Y-m-d');
        if($startDate < $offlineCourse->start_date && !$errors[2]) {
            $errors[2] = '<strong class="text-danger"> Không được nhỏ hơn ngày bắt đầu của khóa học </strong>';
            $error = true;
        }
        if($startDate > $offlineCourse->end_date && !$errors[2]) {
            $errors[2] = '<strong class="text-danger"> Không được lớn hơn ngày kết thúc của khóa học </strong>';
            $error = true;
        }

        // check giờ học trong lịch học
        if($timeStart >= $timeEnd && !$errors[4]){
            $errors[4] = '<strong class="text-danger"> Giờ kết thúc phải sau Giờ bắt đầu </strong>';
            $error = true;
        }

        $schedules = OfflineSchedule::where('course_id', $this->course_id)->where('class_id', $checkCodeClass->id)->where('lesson_date', $startDate)->get(['start_time','end_time']);
        if(!empty($schedules)) {
            foreach($schedules as $item) {
                if (get_date($item->start_time, 'H:i') <= $timeStart && $timeStart <= get_date($item->end_time, 'H:i')){
                    $errors[3] = '<strong class="text-danger"> Giờ học đã tồn tại </strong>';
                    $error = true;
                }

                if (get_date($item->start_time, 'H:i') <= $timeEnd && $timeEnd <= get_date($item->end_time, 'H:i')){
                    $errors[4] = '<strong class="text-danger"> Giờ học đã tồn tại </strong>';
                    $error = true;
                }

                if ($timeStart <= get_date($item->start_time, 'H:i') && get_date($item->end_time, 'H:i') <= $timeEnd){
                    $errors[3] = '<strong class="text-danger"> Giờ học đã tồn tại </strong>';
                    $errors[4] = '<strong class="text-danger"> Giờ học đã tồn tại </strong>';
                    $error = true;
                }
            }
        }

        // check giảng viên tồn tại
        $checkTeacher = TrainingTeacher::where('code', $row[5])->first(['id']);
        if(empty($checkTeacher) && !$errors[5]) {
            $errors[5] = '<strong class="text-danger"> Không tồn tại </strong>';
            $error = true;
        } 

        // check giảng viên nằm trong khóa học
        $teachersCourse = [OfflineTeacher::where('course_id', $this->course_id)->pluck('teacher_id')->toArray()];
        if(in_array((string)$checkTeacher->id, $teachersCourse) && !$errors[5]) {
            $errors[5] = '<strong class="text-danger"> Không nằm trong danh sách giảng viên khóa học </strong>';
            $error = true;
        }
        
        //ktra trợ giảng
        if(isset($row[6])) {
            $tutors = explode(',', $row[6]);
            foreach($tutors as $tutor) {
                $checkTutor = TrainingTeacher::where('code', $row[6])->first();
                if(empty($checkTutor) && !$errors[6]) {
                    $errors[6] = '<strong class="text-danger"> Không tồn tại </strong>';
                    $error = true;
                    break;
                } 
                // check trợ giảng nằm trong khóa học
                if(!in_array($checkTutor->id, $teacherCourse) && !$errors[6]) {
                    $errors[6] = '<strong class="text-danger"> Không nằm trong danh sách giảng viên khóa học </strong>';
                    $error = true;
                }
            }
        }

        // check giảng viên tồn tại giờ học trong các khóa học
        $scheduleAllCourses = OfflineSchedule::where('lesson_date', $startDate)->where('course_id', '!=', $this->course_id)->get(['course_id','start_time','end_time', 'teacher_main_id']);
        if(!empty($scheduleAllCourses)) {
            foreach($scheduleAllCourses as $value) {
                if (get_date($value->start_time, 'H:i') < $timeStart && $timeStart < get_date($value->end_time, 'H:i') && $checkTeacher->id == $value->teacher_main_id && !$errors[5]){
                    $course = OfflineCourse::find($value->course_id, ['name']);
                    $errors[5] = '<strong class="text-danger"> Đã đăng ký giảng dạy khóa học: ' . $course->name .' </strong>';
                    $error = true;
                }
                if (get_date($value->start_time, 'H:i') < $timeEnd && $timeEnd < get_date($value->end_time, 'H:i') && $checkTeacher->id == $value->teacher_main_id && !$errors[5]){
                    $course = OfflineCourse::find($value->course_id, ['name']);
                    $errors[5] = '<strong class="text-danger"> Đã đăng ký giảng dạy khóa học: ' . $course->name .' </strong>';
                    $error = true;
                }
            }
        }

        //check địa điểm đào tạo
        if(isset($row[9])) {
            $checkTrainingLocation = TrainingLocation::where('code', $row[9])->first(['id']);
            if(empty($checkTrainingLocation)) {
                $errors[9] = '<strong class="text-danger"> Không tồn tại </strong>';
                $error = true;
            } 
        }

        if($error) {
            $this->fail += 1;
        } else {
            $this->success += 1;

            $model = new PreviewImport();
            $model->name_import = 'schedule';
            $model->column1 = $timeStart;
            $model->column2 = $timeEnd;
            $model->column3 = $startDate;
            $model->column4 = $checkTeacher->id;
            $model->column5 = !empty($row[6]) ? $row[6] : null;
            $model->column6 = (int)$row[7];
            $model->column7 = (int)$row[8];
            $model->column8 = 1;
            $model->column9 = $this->course_id;
            $model->column10 = $checkCodeClass->id;
            $model->column11 = isset($row[9]) ? $checkTrainingLocation->id : $offlineCourse->training_location_id;
            $model->save();
        }

        $this->data[] = [
            $row[0],
            $errors[1] ? $errors[1] : $row[1],
            $errors[2] ? $errors[2] : $row[2],
            $errors[3] ? $errors[3] : $row[3],
            $errors[4] ? $errors[4] : $row[4],
            $errors[5] ? $errors[5] : $row[5],
            $errors[6] ? $errors[6] : ($row[6] ? $row[6] : '-'),
            $errors[7] ? $errors[7] : ($row[7] ? $row[7] : '-'),
            $errors[8] ? $errors[8] : ($row[8] ? $row[8] : '-'),
            $errors[9] ? $errors[9] : ($row[9] ? $row[9] : '-'),
            $error ? 'error' : 'success'
        ];
    }

    public function startRow(): int
    {
        return 3;
    }
}
