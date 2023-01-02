<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingQuestionAnswer extends Model
{
    use Cachable;
    protected $table = 'el_rating_question_answer';
    protected $fillable = [
        'name',
        'question_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans('latraining.answer'),
            'question_id' => trans('latraining.question'),
        ];
    }

    public static function getAnswer($question_id)
    {
        $query = self::query();
        return $query->select(['id', 'name', 'is_text'])
            ->where('question_id', '=', $question_id)
            ->get();
    }
}
