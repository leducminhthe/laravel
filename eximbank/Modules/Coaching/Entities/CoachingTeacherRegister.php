<?php

namespace Modules\Coaching\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class CoachingTeacherRegister extends BaseModel
{
    protected $table = "el_coaching_teacher_register";
    protected $primaryKey = 'id';
    protected $fillable = [
        'coaching_teacher_id',
        'content',
        'start_date',
        'end_date',
        'training_objectives',
        'score_training_objectives',
        'students',
        'comment_status_student',
        'score_comment_status_student',
        'plan_content',
        'plan_start',
        'plan_perform',
        'plan_note',
        'coaching_mentor_method_id',
        'teacher_comment',
        'score_teacher_comment',
        'note_teacher_comment',
        'metor_again',
        'student_comment',
        'score_student_comment',
        'note_student_comment',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public static function getAttributeName() {
        return [
            'coaching_teacher_id' => 'Coacher',
            'content' => trans('latraining.content'),
            'start_date' => trans('latraining.start_date'),
            'end_date' => trans('latraining.end_date'),
        ];
    }

    public function coaching_teacher()
    {
        return $this->belongsTo(CoachingTeacher::class, 'coaching_teacher_id', 'id');
    }

    public function coaching_mentor_method()
    {
        return $this->belongsTo(CoachingMentorMethod::class, 'coaching_mentor_method_id', 'id');
    }
}
