<?php

namespace Modules\DashboardUnit\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DashboardUnitUserByQuizType extends Model
{
    use Cachable;
    protected $table = 'el_dashboard_unit_user_by_quiz_type';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'unit_code',
        'quiz_type_id',
        'quiz_type_name',
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
