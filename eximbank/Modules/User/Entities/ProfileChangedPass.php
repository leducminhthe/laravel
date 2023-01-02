<?php

namespace Modules\User\Entities;

use App\Permission;
use Illuminate\Database\Eloquent\Model;

class ProfileChangedPass extends Model
{
    protected $table = 'el_profile_changed_pass';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'status',
    ];

    public static function checkChangedPass($user_id){
        if ($user_id <= 2){
            return true;
        }

        $check = self::query()
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 1);

        if ($check->exists()){
            return true;
        }

        return false;
    }
}
