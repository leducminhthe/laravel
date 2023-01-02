<?php
namespace Modules\RegisterTrainingPlan\Imports;

use App\Models\Categories\Area;
use App\Models\Categories\Subject;
use App\Models\Categories\TeacherType;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingObject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\Unit;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Modules\Certificate\Entities\Certificate;
use Modules\Quiz\Entities\Quiz;
use Modules\RegisterTrainingPlan\Entities\RegisterTrainingPlan;

class ImportCourse implements ToModel, WithStartRow
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

        if($index){
            $subject_name = $row[1];
            $course_name = $row[2];
            $start_date = $row[3];
            $end_date = $row[4];
            $course_type = $row[5];
            $training_form_name = $row[6];
            $training_area_name = $row[7];
            $course_employee = $row[8];
            $target = $row[9];
            $content = $row[10];
            $course_time = $row[11];
            $max_student = $row[12];
            $course_belong_to = (int)$row[13];

            $training_area_id = [];

            $training_form = TrainingForm::whereName($training_form_name)->first();
            $subject = Subject::whereName($subject_name)->first();

            if(empty($start_date)){
                $this->errors[] = 'Dòng <b>'. $index .'</b> Ngày bắt đầu không được trống';
                $error = true;
            }
            if(empty($end_date) && $course_type == 2){
                $this->errors[] = 'Dòng <b>'. $index .'</b> Ngày kết thúc không được trống';
                $error = true;
            }
            if(isset($end_date)){
                if(date_convert($end_date, '23:59:59') < date_convert($start_date)){
                    $this->errors[] = 'Dòng <b>'. $index .'</b> Ngày kết thúc phải sau Ngày bắt đầu';
                    $error = true;
                }
            }
            if(!in_array($course_type, [1,2]) || empty($course_type)){
                $this->errors[] = 'Dòng <b>'. $index .'</b> Hình thức không tồn tại';
                $error = true;
            }
            if(empty($training_form)){
                $this->errors[] = 'Loại hình đào tạo <b>'. $training_form_name .'</b> dòng <b>'. $index .'</b> không tồn tại';
                $error = true;
            }
            if(empty($training_area_name)){
                $this->errors[] = 'Dòng <b>'. $index .'</b> Khu vực đào tạo không được trống';
                $error = true;
            }else{
                $training_area = Area::whereName($training_area_name)->first();
                if($training_area){
                    $training_area_id[] = $training_area->id;
                }else{
                    $this->errors[] = 'Khu vực đào tạo <b>'. $training_area_name .'</b> dòng <b>'. $index .'</b> không tồn tại';
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

            if($error) {
                return null;
            }

            $model = new RegisterTrainingPlan();
            $model->training_program_id = $subject->training_program_id;
            $model->subject_id = $subject->id;
            $model->level_subject_id = $subject->level_subject_id;
            $model->name = isset($course_name) ? $course_name : $subject_name;
            $model->start_date = date_convert($start_date);
            $model->end_date = isset($end_date) ? date_convert($end_date, '23:59:59') : null;
            $model->course_type = (int)$course_type;
            $model->training_form_id = $training_form->id;
            $model->training_area_id = isset($training_area_name) ? json_encode($training_area_id) : '';
            $model->course_employee = (int)$course_employee;
            $model->teacher_id = '';
            $model->target = $target;
            $model->content = $content;
            $model->course_time = $course_time;
            $model->max_student = isset($max_student) ? (int)$max_student : 0;
            $model->course_belong_to = isset($course_belong_to) ? $course_belong_to : 1;
            $model->created_by = profile()->user_id;
            $model->updated_by = profile()->user_id;
            $model->save();
        }
    }

    public function startRow(): int
    {
        return 2;
    }

}
