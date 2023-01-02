<?php

namespace Modules\ReportNew\Entities;

use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;

class BC02 extends Model
{
    public static function sql($from_date, $to_date, $type_id, $role_id, $quiz_id)
    {
        $dbprefix = \DB::getTablePrefix();
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        QuizAttempts::addGlobalScope(new DraftScope());
        $query = QuizAttempts::query();
        $query->select([
            'el_quiz_attempts.*',
            'b.type_id',
            'b.limit_time',
        ]);
        $query->leftJoin('el_quiz AS b', 'b.id', '=', 'el_quiz_attempts.quiz_id');
        $query->where('el_quiz_attempts.user_id', '>', 2);

        if ($quiz_id){
            $query->where('b.id', '=', $quiz_id);
        }

        if ($from_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'b.id)'), '>=', $from_date);
        }

        if ($to_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'b.id)'), '<=', $to_date);
        }

        if ($type_id){
            $query->where('b.type_id', '=', $type_id);
        }

        return $query;
    }

}
