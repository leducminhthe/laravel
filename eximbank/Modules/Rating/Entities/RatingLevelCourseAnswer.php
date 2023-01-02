<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingLevelCourseAnswer extends Model
{
    use Cachable;
    protected $table = 'el_rating_level_course_answer';
    protected $fillable = [
        'course_question_id',
        'answer_id',
        'answer_name',
        'text_answer',
        'check_answer_matrix',
        'icon',
    ];
    protected $primaryKey = 'id';
}
