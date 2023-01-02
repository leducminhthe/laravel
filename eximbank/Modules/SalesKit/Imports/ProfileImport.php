<?php
namespace Modules\SalesKit\Imports;

use Modules\SalesKit\Entities\SalesKit;
use Modules\SalesKit\Entities\SalesKitObject;
use App\Models\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProfileImport implements ToModel, WithStartRow
{
    public $errors;
    public $saleskit_id;

    public function __construct($saleskit_id)
    {
        $this->errors = [];
        $this->saleskit_id = $saleskit_id;
    }

    public function model(array $row)
    {
        $error = false;
        $user_code = $row[1];
        $status = $row[2];

        $profile = Profile::where('code', '=', $user_code)->first();

        if($profile){
            $saleskit = SalesKitObject::where('user_id', '=', $profile->user_id)->where('saleskit_id', '=', $this->saleskit_id)->first();

            if ($saleskit) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã được thêm';
                $error = true;
            }
        }

        if (!in_array($status, [1,2,3])){
            $this->errors[] = 'Quyền không đúng';
            $error = true;
        }

        if (empty($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        SalesKitObject::create([
            'user_id' =>(int) $profile->user_id,
            'saleskit_id' => $this->saleskit_id,
            'status' => ($status ? $status : 3),
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
