<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;

class BC28 extends Model
{
    public static function sql($quiz_id, $quiz_part)
    {
        $quiz_parts = explode(',', $quiz_part);

        QuizRegister::addGlobalScope(new CompanyScope());
        $query = QuizRegister::query();
        $query->select([
            'el_quiz_register.*',
            'quiz.name as quiz_name',
            'quiz.pass_score',
            'quiz.limit_time',
            'quiz.grade_methor',
            'part.name as part_name',
            'quiz_type.name as quiz_type_name',
            'result.reexamine',
            'result.result',
            'result.grade',
        ]);
        $query->join('el_quiz as quiz', 'quiz.id', 'el_quiz_register.quiz_id');
        $query->join('el_quiz_part as part', 'part.id', 'el_quiz_register.part_id');
        $query->leftjoin('el_quiz_type as quiz_type', 'quiz.type_id', 'quiz_type.id');
        $query->leftjoin('el_quiz_result as result', function($join) {
            $join->on('result.quiz_id', '=', 'el_quiz_register.quiz_id');
            $join->on('result.user_id', '=', 'el_quiz_register.user_id');
            $join->on('result.type', '=', 'el_quiz_register.type');
        });
        $query->where('el_quiz_register.quiz_id', '=', $quiz_id);
        
        if(isset($quiz_part)) {
            $query->whereIn('el_quiz_register.part_id', $quiz_parts);
        }

        $query->where('el_quiz_register.user_id', '>', 2);
        /*$query->where('type', '=',1);*/

        return $query;
    }

}
