<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BC26 extends Model
{
    public static function sql($from_date, $to_date, $user_id = null)
    {
        if($user_id != profile()->user_id) {
            ReportNewExportBC11::addGlobalScope(new CompanyScope('unit_id_1'));
        }
        $query = ReportNewExportBC11::query();
        $query->select([
            'el_report_new_export_bc11.*',
            'b.start_time as schedule_start_time',
            'b.end_time as schedule_end_time',
            'b.practical_teaching',
            'c.name as class_name',
            'c.id as class_id',
            'd.num_hour',
        ])->disableCache();
        $query->from('el_report_new_export_bc11');
        $query->join('el_offline_schedule as b', 'b.id', '=', 'el_report_new_export_bc11.schedule_id');
        $query->join('offline_course_class as c', 'c.id', '=', 'b.class_id');
        $query->leftjoin('el_training_teacher_history as d', function($join) {
            $join->on('d.course_id', 'el_report_new_export_bc11.course_id');
            $join->on('d.schedule_id', 'el_report_new_export_bc11.schedule_id');
            $join->on('d.teacher_id', 'el_report_new_export_bc11.training_teacher_id');
        });
        $query->where('el_report_new_export_bc11.user_id', '>', 2);

        if ($user_id){
            $query->where('el_report_new_export_bc11.user_id', '=', $user_id);
        }
        if ($from_date){
            $query->where('el_report_new_export_bc11.start_date', '>=', date_convert($from_date));
        }
        if ($to_date){
            $query->where('el_report_new_export_bc11.end_date', '<=', date_convert($to_date, '23:59:59'));
        }
        return $query;
    }

}
