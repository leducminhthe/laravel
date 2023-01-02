<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEducatePlanCostModel extends Model
{
    use HasFactory;
    protected $table = "el_course_educate_plan_cost";
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'cost_id',
        'plan_amount',
        'actual_amount',
        'notes',
    ];
}
