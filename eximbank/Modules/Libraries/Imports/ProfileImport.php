<?php
namespace Modules\Libraries\Imports;

use Modules\Libraries\Entities\Libraries;
use Modules\Libraries\Entities\LibrariesObject;
use App\Models\Profile;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProfileImport implements ToModel, WithStartRow
{
    public $errors;
    public $libraries_id;

    public function __construct($libraries_id, $type_import)
    {
        $this->errors = [];
        $this->libraries_id = $libraries_id;
        $this->type_import = $type_import;
    }

    public function model(array $row)
    {
        $error = false;
        $value_type = $row[1];
        $status = $row[2];

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
            $libraries = LibrariesObject::where('user_id', '=', $profile->user_id)->where('libraries_id', '=', $this->libraries_id)->first();

            if ($libraries) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã được thêm';
                $error = true;
            }
        }

        if (!in_array($status, [1,2,3])){
            $this->errors[] = 'Phân quyền không đúng';
            $error = true;
        }

        if (!isset($profile)) {
            $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        $lib = Libraries::find($this->libraries_id);

        LibrariesObject::create([
            'user_id' =>(int) $profile->user_id,
            'libraries_id' => $this->libraries_id,
            'status' => $lib->type == 4 ? 1 : ($status ? $status : 3),
            'type' => $lib->type,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
