<?php

namespace Modules\Report\Entities;

use App\Models\CacheModel;
use App\Models\Permission;
use App\Models\Categories\UnitManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;

class BC07 extends Model
{
    public static function sql($course)
    {
        $prefix = DB::getTablePrefix();
        $query = OfflineRegister::query();
        $query->select([
            'b.id',
            'b.user_id',
            'b.code',
            'b.full_name',
            'b.title_name',
            'b.email',
            'c.score',
            'c.result',
            'c.note',
            'd.commit_date',
            DB::raw("CASE WHEN {$prefix}c.result = 1 THEN 'X' ELSE '' END as pass"),
            DB::raw("CASE WHEN NULLIF({$prefix}c.result,0) = 0 THEN 'X' ELSE '' END as fail")
        ]);
        $query->from('el_offline_register as a')
            ->join("el_profile_view as b",'b.user_id','=','a.user_id')
            ->leftJoin('el_offline_result as c','c.register_id','=','a.id')
            ->leftJoin('el_indemnify as d', function ($join){
                $join->on('d.user_id','=','a.user_id');
                $join->on('d.course_id','=','a.course_id');
            })
            ->where('a.course_id','=', $course)
            ->where('a.status','=',1);

        return $query;
    }
    public static function getCourseInfo($course)
    {
        return OfflineCourse::query()
            ->select([
                "a.code",
                "a.name",
                "a.start_date",
                "a.end_date",
                "b.name as training_location",
                "a.cost_class",
                "a.course_time"
            ])
            ->from('el_offline_course as a')
            ->leftJoin('el_training_location as b', 'b.id','=','a.training_location_id')
            ->where('a.id', '=', $course)
            ->first();
    }
}
