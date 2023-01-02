<?php

namespace Modules\Quiz\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class QuizPermissionTeacher extends Model
{
    use Cachable;
    protected $table = 'el_quiz_permission_teacher';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quiz_id',
        'teacher_id',
        'question_id',
    ];
}
