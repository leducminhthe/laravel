<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectPrerequisiteCourse extends Model
{
    protected $table = 'subject_prerequisite_courses';
    protected $table_name = "Điều kiện tiên quyết của khóa học";
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'course_type',
        'subject_prerequisite',
        'finish_and_score',
        'date_finish_prerequisite',
        'score_prerequisite',
        'select_subject_prerequisite',
        'status_title',
        'title_id',
        'select_title',
        'date_title_appointment',
        'select_date_title_appointment',
        'status_join_company',
        'join_company',
    ];
}
