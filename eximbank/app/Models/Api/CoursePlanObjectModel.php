<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePlanObjectModel extends Model
{
    use HasFactory;
    protected $table = 'el_course_plan_object';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_type',
        'title_id',
        'unit_id',
        'unit_level',
        'type',
        'created_by',
        'updated_by',
    ];
}
