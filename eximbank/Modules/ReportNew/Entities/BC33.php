<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Survey\Entities\SurveyUser;

class BC33 extends Model
{
    public static function sql($survey_id)
    {
        $query = SurveyUser::query()
        ->select(['a.send', 'b.*', 'a.id as survey_user_id', 'c.id as cate_id'])
        ->from('el_survey_user as a')
        ->leftJoin('el_profile_view as b', 'b.user_id', '=', 'a.user_id')
        ->leftJoin('el_survey_template2_question_category as c', function ($join){
            $join->on('c.template_id', '=', 'a.template_id');
            $join->on('c.survey_id', '=', 'a.survey_id');
        })
        ->where('a.survey_id', '=', $survey_id);

        return $query;
    }
}
