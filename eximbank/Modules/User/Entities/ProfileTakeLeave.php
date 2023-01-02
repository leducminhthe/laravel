<?php

namespace Modules\User\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ProfileTakeLeave extends Model
{
    use Cachable;
    protected $table = 'el_profile_take_leave';
    protected $table_name = 'Nhân viên nghỉ phép';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'full_name',
        'absent_code',
        'absent_name',
        'start_date',
        'end_date',
    ];
}
