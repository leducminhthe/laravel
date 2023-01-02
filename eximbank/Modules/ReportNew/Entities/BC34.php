<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuestionCategory;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizQuestionCategory;

class BC34 extends Model
{
    public static function sql($question_category_id)
    {
        QuestionCategory::addGlobalScope(new DraftScope());
        $query = QuestionCategory::query();
        $query->select([
            'id',
            'name',
        ]);

        if ($question_category_id){
            $query->where(function($sub) use($question_category_id){
                $sub->orWhere('id', '=', $question_category_id);
                $sub->orWhere('parent_id', '=', $question_category_id);
            });
        }

        return $query;
    }

}
