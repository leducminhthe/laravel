<?php
namespace Modules\TargetManager\Imports;

use App\Models\Categories\Titles;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\TargetManager\Entities\TargetManager;
use Modules\TargetManager\Entities\TargetManagerGroup;

class ImportTargetManager implements ToModel, WithStartRow
{
    public $errors;
    public $title_id;

    public function __construct($parent_id, $type_import)
    {
        $this->errors = [];
        $this->parent_id = $parent_id;
        $this->type_import = $type_import;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $error = false;
        $name = trim($row[1]);
        $type = $row[2];
        $group_object_code = $row[3];
        $num_hour_student = $row[4];
        $num_course_student = $row[5];
        $num_hour_teacher = $row[6];
        $num_course_teacher = $row[7];

        $group_object = [];

        if(!in_array($type, [1,2])){
            $this->errors[] = 'Nhóm dòng <b>'. $row[0] .'</b> không đúng';
            $error = true;
        }

        if($type == 1){
            $title_code = explode('; ', $group_object_code);
            foreach($title_code as $code){
                $title = Titles::where('code', $code);
                if(!$title->exists()){
                    $this->errors[] = 'Mã chức danh '. $code .' dòng <b>'. $row[0] .'</b> không tồn tại';
                    $error = true;
                }else{
                    $group_object[] = $title->first()->id;
                }
            }
        }else{
            $user_code = explode('; ', $group_object_code);
            foreach($user_code as $code){
                if($this->type_import == 1) {
                    $name_type = 'Mã nhân viên';
                    $profile = Profile::where('code', '=', $code)->first(['user_id']);
                } else if ($this->type_import == 2) {
                    $name_type = 'Username';
                    $profile = Profile::query()
                    ->select(['user_id'])
                    ->from('el_profile as profile')
                    ->join('user', 'user.id', '=', 'profile.user_id')
                    ->where('user.username', '=', $code)
                    ->first();
                } else {
                    $name_type = 'Email';
                    $profile = Profile::where('email', '=', $code)->first(['user_id']);
                }

                if(!isset($profile)){
                    $this->errors[] = $name_type . ' ' . $code .' dòng <b>'. $row[0] .'</b> không tồn tại';
                    return null;
                }else{
                    $group_object[] = $profile->user_id;
                }
            }
        }

        if($error) {
            return null;
        }

        $model = new TargetManager();
        $model->parent_id = $this->parent_id;
        $model->name = $name;
        $model->type = (int)$type;
        $model->num_hour_student = (int)$num_hour_student;
        $model->num_course_student = (int)$num_course_student;
        $model->num_hour_teacher = (int)$num_hour_teacher;
        $model->num_course_teacher = (int)$num_course_teacher;
        $model->save();

        if(!empty($group_object)) {
            if($type == 1) {
                foreach($group_object as $title) {
                    $save = new TargetManagerGroup();
                    $save->target_manager_id = $model->id;
                    $save->title_id = $title;
                    $save->save();
                }
            } else {
                foreach($group_object as $user) {
                    $save = new TargetManagerGroup();
                    $save->target_manager_id = $model->id;
                    $save->user_id = $user;
                    $save->save();
                }
            }
        }
    }
}
