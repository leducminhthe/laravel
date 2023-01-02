<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineSurveyUserCategory extends Model
{
    protected $table = 'offline_survey_user_category';
    protected $fillable = [
        'survey_user_id',
        'category_id',
        'category_name',
    ];
    protected $primaryKey = 'id';

    public function questions()
    {
        return $this->hasMany('Modules\Offline\Entities\OfflineSurveyUserQuestion', 'survey_user_category_id');
    }
}
