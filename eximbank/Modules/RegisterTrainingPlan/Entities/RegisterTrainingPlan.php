<?php

namespace Modules\RegisterTrainingPlan\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class RegisterTrainingPlan extends BaseModel
{
    protected $primaryKey = 'id';
    protected $table = 'el_register_training_plan';
    protected $fillable = [
        'course_type',
        'training_program_id',
        'level_subject_id',
        'subject_id',
        'name',
        'start_date',
        'end_date',
        'course_time',
        'target',
        'content',
        'training_form_id',
        'training_area_id',
        'teacher_id',
        'course_employee',
        'max_student',
        'created_by',
        'updated_by',
        'unit_by',
        'send',
        'status',
        'note_status',
        'course_belong_to',
    ];
}
