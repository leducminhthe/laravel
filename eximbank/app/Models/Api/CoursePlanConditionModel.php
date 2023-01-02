<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePlanConditionModel extends Model
{
    use HasFactory;
    protected $table = 'el_course_plan_condition';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_type',
        'ratio',
        'minscore',
        'survey',
        'certificate',
    ];
}
