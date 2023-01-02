<?php

namespace Modules\Offline\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OfflineNewTeacher extends Model
{
    protected $table = 'el_offline_new_teacher';
    protected $fillable = [
        'class_id',
        'course_id',
        'schedule_id',
        'new_teacher_id',
        'cost_new_teacher',
        'practical_teaching_new_teacher',
    ];
    protected $primaryKey = 'id';
}
