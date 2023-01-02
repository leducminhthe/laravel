<?php

namespace Modules\DashboardUnit\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DashboardUnitByCourse extends Model
{
    use Cachable;
    protected $table = 'el_dashboard_unit_by_course';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'unit_name',
        'area_id',
        'total',
        'training_form_id',
        'training_form_name',
        'num_user',
        'num_course',
        'course_employee',
        'month',
        'year',
    ];
}
