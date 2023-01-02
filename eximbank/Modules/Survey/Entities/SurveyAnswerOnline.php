<?php

namespace Modules\Survey\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswerOnline extends Model
{
    use Cachable;
    protected $table = 'el_survey_answer_online';
    protected $fillable = [
        'name',
        'question_id',
        'template_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans('latraining.answer'),
            'question_id' => trans('latraining.question'),
        ];
    }
}
