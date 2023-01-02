<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEducatePlanConditionModel extends Model
{
    use HasFactory;
    protected $table = 'el_course_educate_plan_condition';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'ratio',
        'minscore',
        'survey',
        'certificate',
    ];
}
