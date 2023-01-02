<?php

namespace Modules\Online\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SettingJoinOnlineCourse extends BaseModel
{
    use Cachable;
    protected $table = 'el_setting_join_online_course';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'title_id',
        'title_rank_id',
        'course_complete_id',
        'date_register',
        'date_register_join_company',
        'auto_register',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
