<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineSurveyUserCategory extends Model
{
    protected $table = 'el_online_survey_user_category';
    protected $fillable = [
        'survey_user_id',
        'category_id',
        'category_name',
    ];
    protected $primaryKey = 'id';

    public function questions()
    {
        return $this->hasMany('Modules\Online\Entities\OnlineSurveyUserQuestion', 'survey_user_category_id');
    }
}
