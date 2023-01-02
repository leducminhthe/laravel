<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

class BC09 extends Model
{
    public static function sql($training_area_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id)
    {
        ReportNewExportBC05::addGlobalScope(new CompanyScope('unit_id_1'));
        $query = ReportNewExportBC05::query();
        $query->select([
            'el_report_new_export_bc05.*',
        ]);
        $query->from('el_report_new_export_bc05');
        $query->leftjoin('el_unit_view as unit','unit.id','=','el_report_new_export_bc05.unit_id_1');
        $query->leftjoin('area_view as area','area.id','=','unit.area_id');
        $query->where('el_report_new_export_bc05.course_type', '=', 2);
        $query->where('el_report_new_export_bc05.course_employee', '=', 1);
        $query->where('el_report_new_export_bc05.start_date', '>=', date_convert($from_date));
        $query->where('el_report_new_export_bc05.end_date', '<=', date_convert($to_date));

        if ($training_area_id){
            $areaWhere = Area::generateWhereArea($training_area_id);
            $query->whereRaw($areaWhere);
        }
        if ($training_type_id){
            $query->whereIn('el_report_new_export_bc05.training_type_id', explode(',', $training_type_id));
        }
        if ($title_id){
            $query->whereIn('el_report_new_export_bc05.title_id', explode(',', $title_id));
        }

        if ($unit_id){
            $whereUnit = Unit::generateWhereUnit($unit_id);
            $query->whereRaw($whereUnit);
        }
        return $query;
    }

}
