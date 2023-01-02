<?php

namespace App\Imports;

use App\Models\Categories\LevelSubject;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\Unit;
use App\Notifications\ImportSubjectHasFailed;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingProgram;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportTrainingTeacher implements ToModel, WithStartRow
{
    use Importable;
    public $imported_by;
    public $errors;

    public function __construct($user_id, $type_import)
    {
        $this->errors = [];
        $this->imported_by = $user_id;
        $this->type_import = $type_import;
    }

    public function model(array $row)
    {

        $error = false;
        $value_type = trim($row[1]);

        if($this->type_import == 1) {
            $name_type = 'Mã nhân viên';
            $profile = Profile::where('code', '=', $value_type)->first(['user_id','code','lastname','firstname','email','phone']);
        } else if ($this->type_import == 2) {
            $name_type = 'Username';
            $profile = Profile::query()
            ->select(['user_id','code','lastname','firstname','email','phone'])
            ->from('el_profile as profile')
            ->join('user', 'user.id', '=', 'profile.user_id')
            ->where('user.username', '=', $value_type)
            ->first();
        } else {
            $name_type = 'Email';
            $profile = Profile::where('email', '=', $value_type)->first(['user_id','code','lastname','firstname','email','phone']);
        }

        if (empty($value_type)) {
            $this->errors[] = 'Dòng '. $row[0] .': '. $name_type .' giảng viên không thể trống';
            $error = true;
        }

        if (empty($profile)){
            $this->errors[] = 'Dòng '. $row[0] .': Giảng viên không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        $model = TrainingTeacher::firstOrNew(['code' => $profile->code]);
        $model->user_id = $profile->user_id;
        $model->code = $profile->code;
        $model->name = $profile->lastname . ' ' . $profile->firstname;
        $model->email = $profile->email;
        if (isset($row[4])) {
            $model->phone = $row[4];
        } else {
            $model->phone = $profile->phone;
        }
        $model->status = 1;
        $model->type = 1;
        $model->save();
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
