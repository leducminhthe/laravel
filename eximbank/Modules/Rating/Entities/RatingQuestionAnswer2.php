<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingQuestionAnswer2 extends Model
{
    use Cachable;
    protected $table = 'el_rating_question_answer2';
    protected $fillable = [
        'course_rating_level_id',
        'course_rating_level_object_id',
        'course_id',
        'course_type',
        'code',
        'name',
        'question_id',
        'is_text',
        'is_row',
        'icon',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans('latraining.answer'),
            'question_id' => trans('latraining.question'),
        ];
    }
}
