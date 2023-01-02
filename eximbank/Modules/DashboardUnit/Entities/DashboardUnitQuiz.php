<?php

namespace Modules\DashboardUnit\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DashboardUnitQuiz extends Model
{
    use Cachable;
    protected $table = 'el_dashboard_unit_quiz';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'unit_code',
        'quiz_id',
        'total',
        'unlearned',
        'completed',
        'uncompleted',
        'start_date',
        'end_date',
    ];
}
