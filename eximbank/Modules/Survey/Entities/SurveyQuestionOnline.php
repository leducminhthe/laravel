<?php

namespace Modules\Survey\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestionOnline extends Model
{
    use Cachable;
    protected $table = 'el_survey_question_online';
    protected $fillable = [
        'name',
        'multiple',
        'template_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans('latraining.question'),
        ];
    }
}
