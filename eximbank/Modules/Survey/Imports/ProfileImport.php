<?php
namespace Modules\Survey\Imports;

use Modules\Survey\Entities\SurveyObject;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Categories\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\ProfileView;
use App\Models\User;

class ProfileImport implements ToModel, WithStartRow
{
    public $errors;
    public $survey_id;

    public function __construct($survey_id, $user_role, $type_import)
    {
        $this->errors = [];
        $this->survey_id = $survey_id;
        $this->user_role = $user_role;
        $this->type_import = $type_import;
    }

    public function model(array $row)
    {
        $error = false;
        $value_type = trim($row[1]);

        if($this->type_import == 1) {
            $name_type = 'Mã nhân viên';
            $profile = Profile::where('code', '=', $value_type)->first(['unit_id', 'user_id']);
        } else if ($this->type_import == 2) {
            $name_type = 'Username';
            $profile = Profile::query()
            ->select(['unit_id', 'user_id'])
            ->from('el_profile as profile')
            ->join('user', 'user.id', '=', 'profile.user_id')
            ->where('user.username', '=', $value_type)
            ->first();
        } else {
            $name_type = 'Email';
            $profile = Profile::where('email', '=', $value_type)->first(['unit_id', 'user_id']);
        }

        if($profile){
            $survey = SurveyObject::where('user_id', '=', $profile->user_id)
            ->where('survey_id', '=', $this->survey_id)->first();

            if ($survey) {
                $this->errors[] = 'Dòng <b>'. $row[0] .'</b>: Nhân viên đã được thêm';
                $error = true;
            }
        }

        if (empty($profile)) {
            $this->errors[] = $name_type . ' <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        // kiểm tra nhân viên có thuộc đơn vị quản lý hay ko
        if(!Permission::isAdmin()) {
            if($this->user_role->type == 'group-child') {
                $getArray = Unit::getArrayChild($this->user_role->code);
                array_push($getArray, $this->user_role->unit_id);
                if(!in_array((int) $profile->unit_id, $getArray)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                    $error = true;
                }
            } else {
                if($profile->unit_id != $this->user_role->unit_id) {
                    $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                    $error = true;
                }
            }
        }

        if($error) {
            return null;
        }

        SurveyObject::create([
            'user_id' =>(int) $profile->user_id,
            'survey_id' => $this->survey_id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
