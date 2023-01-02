<?php

namespace Modules\DashboardUnit\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DashboardUnitUserByCourseEmployee extends Model
{
    use Cachable;
    protected $table = 'el_dashboard_unit_user_by_course_employee';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'unit_code',
        'course_employee',
        't1',
        't2',
        't3',
        't4',
        't5',
        't6',
        't7',
        't8',
        't9',
        't10',
        't11',
        't12',
        'total',
        'year',
    ];
}
