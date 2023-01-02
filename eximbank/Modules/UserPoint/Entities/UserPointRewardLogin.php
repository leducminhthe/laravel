<?php

namespace Modules\UserPoint\Entities;

use Illuminate\Database\Eloquent\Model;

class UserPointRewardLogin extends Model
{
	protected $table="el_userpoint_reward_login";
    protected $fillable = [
        "start_date", 
        "end_date",
        "number_login",
        "reward_point",
    ];

	public static function getAttributeName() {
        return [
            'start_date' => trans('latraining.start_date'),
            'end_date' => trans('latraining.end_date'),
            'number_login' => 'Số lần đăng nhập',
            'reward_point' => 'Điểm thưởng',
        ];
    }

}
