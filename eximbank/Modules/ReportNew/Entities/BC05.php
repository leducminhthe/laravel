<?php

namespace Modules\ReportNew\Entities;

use App\Models\Categories\Unit;
use App\Models\Categories\Area;
use App\Models\Categories\UnitManager;
use App\Models\Permission;
use App\Models\Profile;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BC05 extends Model
{
    public static function sql($course_type, $subject_id, $from_date, $to_date, $training_type_id, $title_id, $unit_id, $area_id)
    {
        $unit_manager = Permission::isUnitManager();
        $allUnitId = [];
        if($unit_manager){ //Check là TĐV
            $userManeger = Profile::whereUserId(profile()->user_id)->first(['code']);
            $unitUserManagers = UnitManager::whereUserCode($userManeger->code)->get(); //Lấy các đơn vị quản lý
            foreach($unitUserManagers as $unitUserManager){
                $allUnitId[] = (int)$unitUserManager->unit_id;
                $childArrUnitManager = Unit::getArrayChild(@$unitUserManager->unit_code); //lấy id đơn vị con

                $allUnitId = array_merge($allUnitId, $childArrUnitManager); //Gom tổng các đơn vị
            }
        }

        ReportNewExportBC05::addGlobalScope(new CompanyScope('unit_id_1'));
        $query = ReportNewExportBC05::query();
        $query->select([
            'el_report_new_export_bc05.*',
            'complete.created_at as time_complete',
            'register.created_at as time_register',
        ]);
        $query->from('el_report_new_export_bc05');
        $query->leftjoin('el_course_register_view as register', function($register) {
            $register->on('register.course_id', '=', 'el_report_new_export_bc05.course_id');
            $register->on('register.course_type', '=', 'el_report_new_export_bc05.course_type');
            $register->on('register.user_id', '=', 'el_report_new_export_bc05.user_id');
        });
        $query->leftJoin('el_course_complete as complete', function($join2) {
            $join2->on('complete.course_id', '=', 'el_report_new_export_bc05.course_id');
            $join2->on('complete.user_id', '=', 'el_report_new_export_bc05.user_id');
            $join2->on('complete.course_type', '=', 'el_report_new_export_bc05.course_type');
        });

        $query->where('el_report_new_export_bc05.user_id', '>', 2);

        if($course_type){
            $query->where('el_report_new_export_bc05.course_type', $course_type);
        }

        if($unit_manager){
            $query->whereIn('el_report_new_export_bc05.unit_id_1', $allUnitId);
        }

        if ($subject_id){
            $query->whereIn('el_report_new_export_bc05.subject_id', explode(',', $subject_id));
        }
        if ($from_date){
            $query->where('el_report_new_export_bc05.start_date', '>=', date_convert($from_date, '00:00:00'));
        }
        if ($to_date){
            $query->where(function ($sub) use ($to_date){
                $sub->orWhereNull('el_report_new_export_bc05.end_date');
                $sub->orWhere('el_report_new_export_bc05.start_date', '<=', date_convert($to_date, '23:59:59'));
            });
        }
        if ($training_type_id){
            $query->whereIn('el_report_new_export_bc05.training_type_id', explode(',', $training_type_id));
        }
        if ($title_id){
            $query->whereIn('el_report_new_export_bc05.title_id', explode(',', $title_id));
        }
        if ($area_id) {
            $areaWhere = Area::generateWhereArea($area_id);
            $query->whereRaw($areaWhere);
        }
        if ($unit_id){
            $whereUnit = Unit::generateWhereUnit($unit_id);
            $query->whereRaw($whereUnit);
            /*$unit_child = Unit::getArrayChild($unit->code);
            $query->where(function ($sub_query) use ($unit_child, $unit) {
                $sub_query->orWhereIn('el_report_new_export_bc05.unit_id_1', $unit_child);
                $sub_query->orWhere('el_report_new_export_bc05.unit_id_1', '=', $unit->id);
            });*/
        }

        return $query;
    }

}
