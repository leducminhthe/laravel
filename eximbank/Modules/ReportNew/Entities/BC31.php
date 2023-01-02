<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\RattingCourse;
use App\Models\TotalTimeUserLearnInYear;

class BC31 extends Model
{
    public static function sql($title_id, $unit_id, $user_id, $year)
    {
        $query = TotalTimeUserLearnInYear::query();
        $query->select([
            'time.*',
            'title.user_time_kpi',
        ]);
        $query->from('el_total_time_user_learn_year as time');
        $query->leftJoin('el_titles as title', 'title.id', '=', 'time.title_id');
        $query->where('time.year', $year);
        $query->where('time.user_id', '>', 2);

        if ($title_id){
            $query->whereIn('time.title_id', $title_id);
        }

        if ($unit_id){
            $query->where('time.unit_id', $unit_id);
        }

        if ($user_id){
            $query->where('time.user_id', $user_id);
        }
        $query->orderBy('time.user_id', 'asc');

        return $query;
    }
}
