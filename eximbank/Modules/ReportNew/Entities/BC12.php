<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Models\CourseView;
use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;

class BC12 extends Model
{
    public static function sql($training_area_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id)
    {
        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->select([
            'el_course_view.id',
            'el_course_view.course_id',
            'el_course_view.course_type',
            'b.user_id',
        ]);
        $query->join('el_course_register_view as b', function ($sub){
            $sub->on('b.course_id','=','el_course_view.course_id');
            $sub->on('b.course_type','=','el_course_view.course_type');
            $sub->where('b.course_type', '=', 2);
        });
        $query->leftjoin('el_unit_view as unit','unit.id','=','b.unit_id');
        $query->leftjoin('area_view as area','area.id','=','unit.area_id');
        $query->where('el_course_view.status', '=', 1);
        $query->where('el_course_view.isopen', '=', 1);
        $query->where('el_course_view.course_type', '=', 2);
        $query->where('b.user_id', '>', 2);
        $query->whereExists(function ($sub){
            $sub->select(['id'])
                ->from('el_offline_result as result')
                ->whereColumn('result.user_id', '=', 'b.user_id')
                ->whereColumn('result.course_id', '=', 'b.course_id');
        });
        $query->where('el_course_view.offline', '=', 0);
        $query->where('el_course_view.start_date', '>=', date_convert($from_date));
        $query->where('el_course_view.end_date', '<=', date_convert($to_date, '23:59:59'));
        if ($training_area_id){
            $areaWhere = Area::generateWhereArea($training_area_id);
            $query->whereRaw($areaWhere);
        }
        if ($training_type_id){
            $query->whereIn('el_course_view.training_type_id', explode(',', $training_type_id));
        }
        if ($title_id){
            $query->whereIn('b.title_id', explode(',', $title_id));
        }
        if ($unit_id){
            $whereUnit = Unit::generateWhereUnit($unit_id);
            $query->whereRaw($whereUnit);
        }

        return $query;
    }

}
