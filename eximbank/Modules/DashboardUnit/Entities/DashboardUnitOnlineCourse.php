<?php

namespace Modules\DashboardUnit\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DashboardUnitOnlineCourse extends Model
{
    use Cachable;
    protected $table = 'el_dashboard_unit_online_course';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'unit_code',
        'course_id',
        'total',
        'unlearned',
        'studying',
        'completed',
        'uncompleted',
        'start_date',
        'end_date',
    ];
}
