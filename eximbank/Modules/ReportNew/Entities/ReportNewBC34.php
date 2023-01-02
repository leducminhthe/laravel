<?php

namespace Modules\ReportNew\Entities;

use Illuminate\Database\Eloquent\Model;

class ReportNewBC34 extends Model
{
    protected $table = 'el_report_new_bc34';
    protected $primaryKey = 'id';
    protected $fillable = [
        'category_id',
        'scoring_question_used',
        'question_graded_used',
        'scoring_question_correct',
    ];
}
