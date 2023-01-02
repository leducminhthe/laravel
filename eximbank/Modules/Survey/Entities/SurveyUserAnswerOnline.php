<?php

namespace Modules\Survey\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SurveyUserAnswerOnline extends Model
{
    use Cachable;
    protected $table = 'el_survey_user_answer_online';
    protected $fillable = [
        'user_id',
        'answer_id',
        'survey_id',
    ];
    protected $primaryKey = 'id';
}
