<?php
namespace App\Imports;

use App\Models\Categories\SubjectTypeObject;
use App\Models\Profile;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProfileImport implements ToModel, WithStartRow
{
    public $errors;
    public $subject_type_id;

    public function __construct($subject_type_id, $type_import)
    {
        $this->errors = [];
        $this->subject_type_id = $subject_type_id;
        $this->type_import = $type_import;
    }

    public function model(array $row)
    {
        $error = false;
        $value_type = trim($row[1]);

        if($this->type_import == 1) {
            $name_type = 'Mã nhân viên';
            $profile = Profile::where('code', '=', $value_type)->first(['user_id']);
        } else if ($this->type_import == 2) {
            $name_type = 'Username';
            $profile = Profile::query()
            ->select(['user_id'])
            ->from('el_profile as profile')
            ->join('user', 'user.id', '=', 'profile.user_id')
            ->where('user.username', '=', $value_type)
            ->first();
        } else {
            $name_type = 'Email';
            $profile = Profile::where('email', '=', $value_type)->first(['user_id']);
        }
        
        if (!isset($profile)) {
            $this->errors[] = 'Dòng: <b>'. $row[0] .'</b> '. $name_type .' không tồn tại';
            $error = true;
        } else {
            $survey = SubjectTypeObject::where('user_id', '=', $profile->user_id) ->where('subject_type_id', '=', $this->subject_type_id)->first();

            if ($survey) {
                $this->errors[] = 'Dòng <b>'. $row[0] .'</b>: Nhân viên đã được thêm';
                $error = true;
            }
        }

        if($error) {
            return null;
        }

        SubjectTypeObject::create([
            'user_id' =>(int) $profile->user_id,
            'subject_type_id' => $this->subject_type_id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
