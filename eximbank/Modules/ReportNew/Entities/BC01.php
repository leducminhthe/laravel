<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\Quiz;

class BC01 extends Model
{
    public static function sql($from_date, $to_date, $type_id, $role_id, $quiz_id)
    {
        $dbprefix = \DB::getTablePrefix();
        $from_date = date_convert($from_date);
        $to_date = date_convert($to_date,'23:59:59');

        Quiz::addGlobalScope(new DraftScope());
        $query = Quiz::query();
        $query->select([
            'el_quiz.*',
        ]);
        if ($quiz_id){
            $query->where('el_quiz.id', '=', $quiz_id);
        }

        if ($from_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'el_quiz.id)'), '>=', $from_date);
        }

        if ($to_date) {
            $query->where(\DB::raw('(select MIN(start_date)
            from '.$dbprefix.'el_quiz_part
            where quiz_id = '.$dbprefix.'el_quiz.id)'), '<=', $to_date);
        }
        if ($type_id){
            $query->where('el_quiz.type_id', '=', $type_id);
        }

        return $query;
    }

}
