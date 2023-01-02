<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class TrainingTeacherRegister extends Model
{
    use Cachable;
    protected $table = 'el_training_teacher_register_schedule';
    protected $fillable = [
        'teacher_id',
        'course_id',
        'class_id',
        'user_id',
        'status',
        'note',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'teacher_id' => trans('latraining.teacher'),
            'course_id' => trans('lamenu.course'),
        ];
    }
}
