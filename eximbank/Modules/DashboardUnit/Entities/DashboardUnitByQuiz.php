<?php

namespace Modules\DashboardUnit\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DashboardUnitByQuiz extends Model
{
    use Cachable;
    protected $table = 'el_dashboard_unit_by_quiz';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'unit_name',
        'area_id',
        'total',
        'quiz_type',
        'quiz_type_name',
        'num_user',
        'num_quiz_part',
        'month',
        'year',
    ];
}
