<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEducatePlanObjectModel extends Model
{
    use HasFactory;
    protected $table = 'el_course_educate_plan_object';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'title_id',
        'unit_id',
        'unit_level',
        'type',
        'created_by',
        'updated_by',
    ];
}
