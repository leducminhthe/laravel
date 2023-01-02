<?php

namespace Modules\Quiz\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class QuizTeacherGraded extends Model
{
    use Cachable;
    protected $table = 'el_quiz_teacher_graded';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quiz_id',
        'part_id',
        'teacher_id',
        'user_id',
        'user_type',
    ];
}
