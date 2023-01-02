<?php
namespace Modules\CoursePlan\Imports;

use App\Models\Categories\Subject;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingObject;
use Modules\Online\Entities\OnlineCourse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Categories\TrainingProgram;
use Illuminate\Support\Facades\Auth;
use Modules\Certificate\Entities\Certificate;
use Modules\CoursePlan\Entities\CoursePlan;

class ImportOnlineCourse implements ToModel, WithStartRow
{
    public $errors;

    public function __construct()
    {
        $this->errors = [];
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
        $is_limit_time = (int)$row[7];
        $start_timeday = $row[8];
        $end_timeday = $row[9];
        $training_form_code = $row[10];
        $max_grades = $row[11];
        $min_grades = $row[12];
        $training_object_code = $row[13];
        $description = $row[14];
        $content = $row[15];
        $auto = (int)$row[16];
        $num_lesson = $row[17];
        $cert_code = $row[18];
        $course_belong_to = $row[19];

        $training_object_id = [];

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

        if($is_limit_time == 1){
            if(empty($start_timeday)){
                $this->errors[] = 'Dòng <b>'. $index .'</b> không được trống';
                $error = true;
            }elseif(empty($end_timeday)){
                $this->errors[] = 'Dòng <b>'. $index .'</b> không được trống';
                $error = true;
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

        if (!in_array($auto, [0, 2]) || is_null($auto)) {
            $this->errors[] = 'Dòng '. $index .': Duyệt khoá không tồn tại';
            $error = true;
        }

        if(isset($cert_code)){
            $cert = Certificate::where('code', $cert_code)->first();
            if(empty($cert)){
                $this->errors[] = 'Mã chứng chỉ <b>'. $cert_code .'</b> dòng <b>'. $index .'</b> không tồn tại';
                $error = true;
            }
        }

        if(!in_array($course_belong_to, [1,2]) || empty($course_belong_to)){
            $this->errors[] = 'Dòng <b>'. $index .'</b> Khoá học thuộc không tồn tại';
            $error = true;
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
        $model->is_limit_time = $is_limit_time;
        $model->start_timeday = $start_timeday;
        $model->end_timeday = $end_timeday;
        $model->training_form_id = $training_form->id;
        $model->max_grades = $max_grades;
        $model->min_grades = $min_grades;
        $model->training_object_id = isset($training_object_code) ? json_encode($training_object_id) : '';
        $model->description = $description;
        $model->content = $content;
        $model->auto = (int)$auto;
        $model->num_lesson = $num_lesson;
        $model->cert_code = isset($cert_code) ? $cert->id : null;
        $model->has_cert = isset($cert_code) ? 1 : 0;
        $model->created_by = profile()->user_id;
        $model->updated_by = profile()->user_id;
        $model->status = 1;
        $model->course_type = 1;
        $model->training_type_id = 1;
        $model->course_belong_to = (int)$course_belong_to;
        $model->save();
    }

    public function startRow(): int
    {
        return 2;
    }

}
