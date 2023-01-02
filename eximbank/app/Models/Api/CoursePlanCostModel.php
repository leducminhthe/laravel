<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePlanCostModel extends Model
{
    use HasFactory;
    protected $table = "el_course_plan_cost";
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_type',
        'cost_id',
        'plan_amount',
        'actual_amount',
        'notes',
    ];
}
