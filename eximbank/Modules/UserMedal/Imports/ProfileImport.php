<?php
namespace Modules\UserMedal\Imports;

use Modules\UserMedal\Entities\UserMedalObject;
use App\Models\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProfileImport implements ToModel, WithStartRow
{
    public $errors;
    public $settings_id;

    public function __construct($settings_id, $type_import)
    {
        $this->errors = [];
        $this->settings_id = $settings_id;
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

        if (!isset($profile)) {
            $this->errors[] = $name_type. ' <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        } else {
            $survey = UserMedalObject::where('user_id', '=', $profile->user_id)
            ->where('settings_id', '=', $this->settings_id)->first();

            if ($survey) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã được thêm';
                $error = true;
            }
        }

        if($error) {
            return null;
        }

        UserMedalObject::create([
            'user_id' =>(int) $profile->user_id,
            'settings_id' => $this->settings_id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
