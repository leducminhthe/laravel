<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Profile;
use App\Models\Categories\Absent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;
use App\Notifications\ImportUserTakeLeaveHasFailed;
use Modules\User\Entities\WorkingProcess;
use Modules\User\Entities\ProfileTakeLeave;
use Maatwebsite\Excel\Concerns\ToModel;

class UserTakeLeave implements ToModel, WithStartRow
{
    use Importable;
    public $imported_by;
    public $errors;

    public function __construct(User $user)
    {
        $this->errors = [];
        $this->imported_by = $user;
    }

    public function model(array $row)
    {
        $error = false;

        $user_code = trim($row[1]);
        $reason_code = $row[2];
        $reason_type = trim($row[3]);
        $start_date = $row[4];
        $end_date = $row[5];
        $errors = [];

        $profile = Profile::where('code','=', $user_code)->first(['user_id','lastname','firstname']);
        if($reason_type == 1) {
            $absent = Absent::where('code','=', $reason_code)->first();

            if (empty($absent)) {
                $this->errors[] = 'Dòng '. $row[0] .': Mã vắng mặt <b>'. $absent .'</b> không tồn tại';
                $error = true;
            }
        }

        if (empty($profile)) {
            $this->errors[] = 'Dòng '. $row[0] .': Mã nhân viên <b>'. $user_code .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        $user_take_leave = new ProfileTakeLeave();
        $user_take_leave->user_id = $profile->user_id;
        $user_take_leave->full_name = $profile->lastname . ' ' . $profile->firstname;
        if($reason_type == 1) {
            $user_take_leave->absent_code = $reason_code;
        } else {
            $user_take_leave->absent_name = $reason_code;
        }
        $user_take_leave->start_date = $start_date ? date_convert($start_date) : null;
        $user_take_leave->end_date = $end_date ? date_convert($end_date, '23:59:59') : null;
        $user_take_leave->save();
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
