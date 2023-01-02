<?php
namespace Modules\CoursePlan\Imports;

use App\Models\Categories\Area;
use App\Models\Categories\Subject;
use App\Models\Categories\TeacherType;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingObject;
use Modules\Online\Entities\OnlineCourse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Modules\Certificate\Entities\Certificate;
use Modules\CoursePlan\Entities\CoursePlan;
use Modules\CoursePlan\Entities\CoursePlanTeacher;
use Modules\Quiz\Entities\Quiz;
use App\Models\User;

class ImportOfflineCourse implements ToModel, WithStartRow
{
    public $errors;

    public function __construct($type_import)
    {
        $this->errors = [];
        $this->type_import = $type_import;
    }

    public function model(array $row)
    {
        $error = false;

        $index = $row[0];
        $training_program_code = $row[1];
        $subject_code = $row[2];
        $course_name = $row[3];
        $start_date = $row[4];
        $end_date = $row[5];
        $register_deadline = $row[6];
        $training_form_code = $row[7];
        $max_grades = $row[8];
        $min_grades = $row[9];
        $max_student = $row[10];
        $training_object_code = $row[11];
        $training_area_code = $row[12];
        $training_location_code = $row[13];
        $training_unit_code = $row[14];
        $training_partner_code = $row[15];
        $training_teacher_type_code = $row[16];
        $course_employee = $row[17];
        $course_action = $row[18];
        $description = $row[19];
        $content = $row[20];
        $num_lesson = $row[21];
        $cert_code = $row[22];
        $quiz_code = $row[23];
        $commit = (int)$row[24];
        $commit_date = $row[25];
        $coefficient = $row[26];
        $teachers = $row[27];
        $course_belong_to = $row[28];

        $training_object_id = [];
        $training_area_id = [];
        $training_unit_id = [];
        $training_partner_id = [];

        $training_program = TrainingProgram::whereCode($training_program_code)->first();
        $training_form = TrainingForm::whereCode($training_form_code)->first();

        if(empty($training_program)){
            $this->errors[] = 'Mã Chủ đề <b>'. $training_program_code .'</b> dòng <b>'. $index .'</b> không tồn tại';
            $error = true;
        }else{
            $subject = Subject::whereCode($subject_code)->whereTrainingProgramId($training_program->id)->first();
            if(empty($subject)){
                $this->errors[] = 'Mã Chuyên đề <b>'. $subject_code .'</b> dòng <b>'. $index .'</b> không thuộc chủ đề <b>'. $training_program_code .'</b>';
                $error = true;
            }
        }
        if(empty($start_date)){
            $this->errors[] = 'Dòng <b>'. $index .'</b> Ngày bắt đầu không được trống';
            $error = true;
        }
        if(empty($end_date)){
            $this->errors[] = 'Dòng <b>'. $index .'</b> Ngày kết thúc không được trống';
            $error = true;
        }
        if(isset($end_date)){
            if(date_convert($end_date, '23:59:59') < date_convert($start_date)){
                $this->errors[] = 'Dòng <b>'. $index .'</b> Ngày kết thúc phải sau Ngày bắt đầu';
                $error = true;
            }

            if(isset($register_deadline)){
                if(date_convert($end_date, '23:59:59') <= date_convert($register_deadline)){
                    $this->errors[] = 'Dòng <b>'. $index .'</b> Hạn đăng ký phải trước Ngày kết thúc';
                    $error = true;
                }
            }
        }
        if(empty($training_form)){
            $this->errors[] = 'Mã loại hình đào tạo <b>'. $training_form_code .'</b> dòng <b>'. $index .'</b> không tồn tại';
            $error = true;
        }
        if(isset($training_object_code)){
            $training_object_array = explode(';', $training_object_code);
            foreach($training_object_array as $code){
                $training_object = TrainingObject::whereCode($code)->first();
                if($training_object){
                    $training_object_id[] = $training_object->id;
                }else{
                    $this->errors[] = 'Mã đối tượng tham gia <b>'. $code .'</b> dòng <b>'. $index .'</b> không tồn tại';
                    $error = true;
                }
            }
        }
        if(empty($training_area_code)){
            $this->errors[] = 'Dòng <b>'. $index .'</b> Mã khu vực đào tạo không được trống';
            $error = true;
        }else{
            $training_area_array = explode(';', $training_area_code);
            foreach($training_area_array as $code){
                $training_area = Area::whereCode($code)->first();
                if($training_area){
                    $training_area_id[] = $training_area->id;
                }else{
                    $this->errors[] = 'Mã khu vực đào tạo <b>'. $code .'</b> dòng <b>'. $index .'</b> không tồn tại';
                    $error = true;
                }
            }
        }
        if(isset($training_location_code)){
            $training_location = TrainingLocation::whereCode($training_location_code)->first();
            if(empty($training_location)){
                $this->errors[] = 'Mã địa điểm đào tạo <b>'. $training_location_code .'</b> dòng <b>'. $index .'</b> không tồn tại';
                $error = true;
            }
        }

        if(isset($training_unit_code)){
            $training_unit_array = explode(';', $training_unit_code);
            foreach($training_unit_array as $code){
                $unit = Unit::whereCode($code)->first();
                if($unit){
                    $training_unit_id[] = $unit->id;
                }else{
                    $this->errors[] = 'Mã đơn vị tổ chức <b>'. $code .'</b> dòng <b>'. $index .'</b> không tồn tại';
                    $error = true;
                }
            }
        }

        if(isset($training_partner_code)){
            $training_partner_array = explode(';', $training_partner_code);
            foreach($training_partner_array as $code){
                $unit = Unit::whereCode($code)->first();
                if($unit){
                    $training_partner_id[] = $unit->id;
                }else{
                    $this->errors[] = 'Mã đơn vị phối hợp <b>'. $code .'</b> dòng <b>'. $index .'</b> không tồn tại';
                    $error = true;
                }
            }
        }

        if(isset($training_teacher_type_code)){
            $training_teacher_type = TeacherType::whereCode($training_teacher_type_code)->first();
            if(empty($training_teacher_type)){
                $this->errors[] = 'Mã loại GV <b>'. $training_teacher_type_code .'</b> dòng <b>'. $index .'</b> không tồn tại';
                $error = true;
            }
        }
        if(!in_array($course_employee, [1,2]) || empty($course_employee)){
            $this->errors[] = 'Dòng <b>'. $index .'</b> Khoá học dành cho không tồn tại';
            $error = true;
        }

        if(!in_array($course_belong_to, [1,2]) || empty($course_belong_to)){
            $this->errors[] = 'Dòng <b>'. $index .'</b> Khoá học thuộc không tồn tại';
            $error = true;
        }

        if(isset($quiz_code)){
            $quiz = Quiz::whereCode($quiz_code)->first();
            if(empty($quiz)){
                $this->errors[] = 'Mã Kỳ thi <b>'. $quiz_code .'</b> dòng <b>'. $index .'</b> không tồn tại';
                $error = true;
            }
        }
        if($commit == 1){
            if(empty($commit_date)){
                $this->errors[] = 'Dòng <b>'. $index .'</b> Ngày thực hiện không được trống';
                $error = true;
            }
            if(empty($coefficient)){
                $this->errors[] = 'Dòng <b>'. $index .'</b> Hệ số K không được trống';
                $error = true;
            }

        }

        if(isset($cert_code)){
            $cert = Certificate::where('code', $cert_code)->first();
            if(empty($cert)){
                $this->errors[] = 'Mã chứng chỉ <b>'. $cert_code .'</b> dòng <b>'. $index .'</b> không tồn tại';
                $error = true;
            }
        }

        if($error) {
            return null;
        }

        $model = new CoursePlan();
        $model->training_program_id = $training_program->id;
        $model->subject_id = $subject->id;
        $model->level_subject_id = $subject->level_subject_id;
        $model->name = isset($course_name) ? $course_name : $subject->name;
        $model->start_date = date_convert($start_date);
        $model->end_date = date_convert($end_date, '23:59:59');
        $model->register_deadline = date_convert($register_deadline);
        $model->training_form_id = $training_form->id;
        $model->max_grades = $max_grades;
        $model->min_grades = $min_grades;
        $model->max_student = isset($max_student) ? $max_student : 0;
        $model->training_object_id = isset($training_object_code) ? json_encode($training_object_id) : '';
        $model->training_area_id = isset($training_area_code) ? json_encode($training_area_id) : '';
        $model->training_unit_type = 1;
        $model->training_unit = isset($training_unit_code) ? json_encode($training_unit_id) : '';
        $model->training_partner_type = 1;
        $model->training_partner_id = isset($training_partner_code) ? json_encode($training_partner_id) : '';
        $model->training_location_id = isset($training_location_code) ? $training_location->id : null;
        $model->teacher_type_id = isset($training_teacher_type_code) ? $training_teacher_type->id : null;
        $model->course_employee = (int)$course_employee;
        $model->course_action = isset($course_action) ? (int)$course_action : 0;
        $model->quiz_id = isset($quiz_code)? $quiz->id : null;
        $model->commit = isset($commit) ? $commit : null;
        $model->commit_date = isset($commit) ? date_convert($commit_date) : null;
        $model->coefficient = isset($commit) ? $coefficient : null;
        $model->description = $description;
        $model->content = $content;
        $model->num_lesson = $num_lesson;
        $model->cert_code = isset($cert_code) ? $cert->id : null;
        $model->has_cert = isset($cert_code) ? 1 : 0;
        $model->created_by = profile()->user_id;
        $model->updated_by = profile()->user_id;
        $model->status = 1;
        $model->course_type = 2;
        $model->training_type_id = 2;
        $model->course_belong_to = (int)$course_belong_to;
        $model->save();

        if($teachers){
            $teachers_array = explode(';', $teachers);
            foreach($teachers_array as $teacher){
                if($this->type_import == 1) {
                    $training_teacher = Profile::query()
                    ->from('el_profile as profile')
                    ->join('el_training_teacher as teacher', 'teacher.user_id', '=', 'profile.user_id')
                    ->where('code', '=', $teacher)
                    ->first(['teacher.id']);
                } else if ($this->type_import == 2) {
                    $training_teacher = User::query()
                    ->from('user')
                    ->join('el_training_teacher as teacher', 'teacher.user_id', '=', 'user.id')
                    ->where('user.username', '=', $teacher)
                    ->first(['teacher.id']);
                } else {
                    $training_teacher = Profile::query()
                    ->from('el_profile as profile')
                    ->join('el_training_teacher as teacher', 'teacher.user_id', '=', 'profile.user_id')
                    ->where('email', '=', $teacher)
                    ->first(['teacher.id']);
                }

                if(!isset($training_teacher)){
                    $query = Profile::query();
                    $query->select(['profile.code', 'profile.firstname', 'profile.lastname', 'profile.user_id', 'profile.phone', 'profile.email']);
                    $query->join('user', 'user.id', '=', 'profile.user_id');
                    if($this->type_import == 1) {
                        $query->where('profile.code', '=', $teacher);
                    } else if ($this->type_import == 2) {
                        $query->where('user.username', '=', $teacher);
                    } else {
                        $query->where('profile.email', '=', $teacher);
                    }
                    $user = $query->first();
                    if($user) {
                        $training_teacher = new TrainingTeacher();
                        $training_teacher->user_id = $user->user_id;
                        $training_teacher->code = $user->code;
                        $training_teacher->name = $user->lastname .' '. $user->firstname;
                        $training_teacher->phone = $user->phone;
                        $training_teacher->email = $user->email;
                        $training_teacher->save();
                    }
                }

                if($training_teacher) {
                    $course_plan_teacher = CoursePlanTeacher::firstOrNew(['teacher_id' => $training_teacher->id, 'course_id' => $model->id, 'course_type' => 2]);
                    $course_plan_teacher->teacher_id = $training_teacher->id;
                    $course_plan_teacher->course_id = $model->id;
                    $course_plan_teacher->course_type = 2;
                    $course_plan_teacher->save();
                }
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }

}
