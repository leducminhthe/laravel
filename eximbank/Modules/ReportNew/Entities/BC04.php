<?php

namespace Modules\ReportNew\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Quiz\Entities\ReportCorrectAnswerRate;

class BC04 extends Model
{
    public static function sql($quiz_template_id)
    {
        $query = ReportCorrectAnswerRate::query()
        ->where('quiz_template_id', '=', $quiz_template_id);

        return $query;
    }

}
