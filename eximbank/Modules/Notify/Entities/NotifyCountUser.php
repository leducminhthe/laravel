<?php

namespace Modules\Notify\Entities;

use Illuminate\Database\Eloquent\Model;

class NotifyCountUser extends Model
{
    protected $table = 'el_notify_count_user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'num_notify',
    ];
}
