<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOldModel extends Model
{
    use HasFactory;
    protected $table = 'el_course_old';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_type',
        'course_code',
        'course_name',
        'start_date',
        'end_date',
        'user_code',
        'full_name',
        'unit',
        'title',
        'data',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
