<?php
namespace Modules\Notify\Imports;

use Modules\Notify\Entities\NotifySendObject;
use App\Models\Profile;
use App\Models\Categories\Unit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Permission;

class ProfileImport implements ToModel, WithStartRow
{
    public $errors;
    public $notify_send_id;
    public $user_role;

    public function __construct($notify_send_id, $user_role, $type_import)
    {
        $this->errors = [];
        $this->notify_send_id = $notify_send_id;
        $this->user_role = $user_role;
        $this->type_import = $type_import;
    }

    public function model(array $row)
    {
        $error = false;
        $value_type = $row[1];

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
            $notify_send = NotifySendObject::where('user_id', '=', $profile->user_id)
            ->where('notify_send_id', '=', $this->notify_send_id)->first();

            if ($notify_send) {
                $this->errors[] = 'Mã nhân viên <b>'. $row[1] .'</b> đã được thêm';
                $error = true;
            }

            if(!Permission::isAdmin()){
                if($this->user_role->type == 'group-child') {
                    $getArray = Unit::getArrayChild($this->user_role->code);
                    array_push($getArray, $this->user_role->unit_id);
                    if(!in_array($profile->unit_id, $getArray)) {
                        $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                        return null;
                    }
                } else {
                    if($profile->unit_id != $this->user_role->unit_id) {
                        $this->errors[] = 'Dòng '. $row[0] .': Nhân viên Không thuộc đơn vị quản lý: '. $this->user_role->name .'';
                        return null;
                    }
                }
            }
        }

        if (empty($profile)) {
            $this->errors[] = $name_type . ' <b>'. $row[1] .'</b> không tồn tại';
            $error = true;
        }

        if($error) {
            return null;
        }

        NotifySendObject::create([
            'user_id' =>(int) $profile->user_id,
            'notify_send_id' => $this->notify_send_id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

}
