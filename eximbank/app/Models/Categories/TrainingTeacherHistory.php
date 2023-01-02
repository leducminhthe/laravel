<?php

namespace App\Models\Categories;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTeacherHistory extends Model
{
    use Cachable;
    protected $table = 'el_training_teacher_history';
    protected $fillable = [
        'teacher_id',
        'teacher_type',
        'course_id',
        'class_id',
        'schedule_id',
        'num_schedule',
        'num_hour',
        'cost',
        'month',
        'year',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'teacher_id' => 'Giảng viên',
            'course_id' => 'Khoá học',
            'num_schedule' => 'Số buổi',
            'num_hour' => 'Số giờ',
        ];
    }
}
