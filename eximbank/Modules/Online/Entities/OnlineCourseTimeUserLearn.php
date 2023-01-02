<?php

namespace Modules\Online\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OnlineCourseTimeUserLearn extends Model
{
    use Cachable;
    protected $table = 'el_online_course_time_user_learn';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'user_id',
        'time',
    ];

    public static function getAttributeName() {
        return [
            'course_id' => trans('lamenu.course'),
            'time' => 'Thời gian',
        ];
    }
}
