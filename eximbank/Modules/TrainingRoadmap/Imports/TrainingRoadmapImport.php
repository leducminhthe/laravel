<?php
namespace Modules\TrainingRoadmap\Imports;

use App\Models\Categories\Titles;
use Modules\TrainingRoadmap\Entities\TrainingRoadmap;
use App\Models\Categories\Subject;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Categories\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TrainingRoadmapImport implements ToModel, WithStartRow
{
    public $errors;
    public $title_id;

    public function __construct()
    {
        $this->errors = [];
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $error = false;
        $title_code = trim($row[1]);
        $subject_code = trim($row[2]);
        $training_form = explode(',', $row[3]);
        $completion_time = $row[4];
        $order = $row[5];
        $content = $row[6];

        if (empty($title_code)){
            $this->errors[] = 'Mã chức danh dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if (empty($subject_code)){
            $this->errors[] = 'Mã tài liệu dòng <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if (empty($training_form)){
            $this->errors[] = 'Hình thức dòng: <b>'. $row[0] .'</b> không được trống';
            $error = true;
        }

        if ( !in_array(1, $training_form) && !in_array(2, $training_form) ){
            $this->errors[] = 'Hình thức dòng: <b>'. $row[0] .'</b> không tồn tại';
            $error = true;
        } 

        $title = Titles::where('code', '=', $title_code)->first();
        if (empty($title)) {
            $this->errors[] = 'Mã chức danh dong: <b>'. $row[0] .'</b> không tồn tại';
            $error = true;
        }

        $subject = Subject::where('code', '=', $subject_code)->first();
        if (empty($subject)) {
            $this->errors[] = 'Mã tài liệu dòng: <b>'. $row[0] .'</b> không tồn tại';
            $error = true;
        }

        // kiểm tra chức danh có thuộc đơn vị quản lý hay ko
        if(!Permission::isAdmin() && isset($title)){
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
                if(!in_array($title->unit_by, $getArray)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Chức danh Không thuộc đơn vị quản lý: '. $user_role->name .'';
                    $error = true;
                }
            } else {
                if($title->unit_by != $user_role->unit_id) {
                    $this->errors[] = 'Dòng '. $row[0] .': Chức danh Không thuộc đơn vị quản lý: '. $user_role->name .'';
                    $error = true;
                }
            }
        }

        if($error) {
            return null;
        }

        $model = TrainingRoadmap::firstOrNew(['title_id' => $title->id, 'subject_id' => $subject->id]);
        $model->training_program_id = $subject->training_program_id;
        $model->title_id = $title->id;
        $model->subject_id = $subject->id;
        $model->training_form = json_encode($training_form);
        $model->completion_time = $completion_time ? $completion_time : null;
        $model->order = $order ? $order : 1;
        $model->content = $content ? $content : null;
        $model->save();
    }
}
