<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ReportNewExportBC26 extends Model
{
    use Cachable;
    protected $table = 'el_report_new_export_bc26';
    protected $primaryKey = 'id';
    protected $fillable = [
        'training_plan_id',
        'subject_id',
        'course_action_1',
        'course_action_2',
        'year',
    ];
}
