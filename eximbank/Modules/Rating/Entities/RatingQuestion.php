<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingQuestion extends Model
{
    use Cachable;
    protected $table = 'el_rating_question';
    protected $fillable = [
        'name',
        'category_id',
        'type',
        'multiple',
        'obligatory'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans('latraining.question'),
            'category_id' => trans('lamenu.category'),
            'type' => trans('lasurvey.question_type'),
        ];
    }

    public static function getQuestion($category_id)
    {
        $query = self::query();
        return $query->select(['id', 'name', 'type', 'multiple'])
            ->where('category_id', '=', $category_id)
            ->get();
    }

    public function answers()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingQuestionAnswer','question_id');
    }

    public function answers_matrix()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingAnswerMatrix','question_id');
    }
}
