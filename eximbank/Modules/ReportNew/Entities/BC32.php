<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\RattingCourse;
use App\Models\TotalTimeUserLearnInYear;

class BC32 extends Model
{
    public static function sql($title_id, $unit_id)
    {
        $query = TotalTimeUserLearnInYear::query();
        $query->where('user_id', '>', 2);

        if ($title_id){
            $query->selectRaw('title_id, title_name, sum(time_second) as sum');
            $query->where('title_id', $title_id);
            $query->groupBy('title_id','title_name');
        }

        if ($unit_id){
            $query->selectRaw('unit_id, unit_name, sum(time_second) as sum');
            $query->where('unit_id', $unit_id);
            $query->groupBy('unit_id','unit_name');
        }

        return $query;
    }
}
