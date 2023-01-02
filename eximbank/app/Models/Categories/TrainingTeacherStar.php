<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTeacherStar extends Model
{
    protected $table = 'el_training_teacher_star';
    protected $fillable = [
        'user_id',
        'teacher_id',
        'num_star',
        'course_id',
        'course_type',
        'class_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'user_id' => 'Học viên',
            'teacher_id' => 'Giảng viên',
            'num_star' => 'Số sao',
            'course_id' => 'Khoá học',
            'course_type' => 'Loại khoá học',
            'class_id' => 'Lớp học',
        ];
    }
}
