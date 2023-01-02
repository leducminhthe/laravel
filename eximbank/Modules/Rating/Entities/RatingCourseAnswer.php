<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingCourseAnswer extends Model
{
    use Cachable;
    protected $table = 'el_rating_course_answer';
    protected $fillable = [
        'course_question_id',
        'answer_id',
        'answer_name',
        'text_answer',
        'check_answer_matrix',
        'icon',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_question_id' => trans('latraining.question'),
            'answer_id' => trans('latraining.answer'),
            'answer_name' => 'Tên câu trả lời',
            'text_answer' => 'Nội dung câu trả lời',
        ];
    }
}
