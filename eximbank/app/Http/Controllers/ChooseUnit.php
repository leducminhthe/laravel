<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use App\Models\Permission;

class ChooseUnit extends Controller
{
    //load_choose_unit(page, unit_id, type, load_more = 0)

    public function chooseUnitModal(Request $request) {
        $multiple = $request->multiple;
        return view('modal.modal_choose_unit', [
            'multiple' => $multiple
        ]);
    }

    public function loadUnitModal(Request $request) {
        $unitIdSelected = $request->unitIdSelected;
        $multiple = $request->multiple;
        $userUnit = $request->userUnit;
        $type = $request->type;
        $level = Unit::getMaxLevelUnit($userUnit);
        if($type == 1) { // default
            Unit::addGlobalScope(new DraftScope('level_'.$level));
            $query = Unit::disableCache()
            ->from('el_unit')
            ->leftJoin('el_permission_type_unit as b', 'el_unit.id','=','b.unit_id')
            ->leftJoin('el_unit as c','el_unit.code','=','c.parent_code')
            ->where('el_unit.id', $userUnit)
                ->select([
                'el_unit.id',
                'el_unit.code',
                'el_unit.name',
                'el_unit.level',
                'el_unit.parent_code',
                'b.type',
                \DB::raw('case when `c`.`code` is not null then 1 else 0 end as unit_child_code')
            ])->distinct();
            $unit = $query->first();

            $unitId = $unit->id;
            $unitName = $unit->name;
            if( ($unit->type=='group-child' || \auth()->user()->isAdmin() ) && !empty($unit->unit_child_code)) {
                $data2 = '<div class="col-1 p-0 text-center choose_unit_child">
                                <i class="fas fa-arrow-right" onclick="load_choose_unit(1, '. $unit->id .', 2, 0)"></i>
                            </div>';
            }
            else {
                $data2 = '';
            }

            if($multiple) {
                $units_selected = explode(',', $unitIdSelected);
                if (in_array($unit->id, $units_selected)) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $html = '<div class="col-11 cursor_pointer select_unit">
                            <input type="checkbox" id="checkbox_unit_'. $unit->id .'" onchange="checkBoxUnitHandle('. $unit->id .')" '. $checked .'>
                            <label class="m-0 cursor_pointer" for="checkbox_unit_'. $unit->id .'">
                                <span class="list-group-item-text">'. $unit->name .'</span>
                            </label>
                        </div>';
            } else {
                $html = '<div class="col-11 cursor_pointer select_unit" onclick="selectUnit('. $unit->id .')">
                            <label class="m-0 cursor_pointer">
                                <span class="list-group-item-text">'. $unit->name .'</span>
                            </label>
                        </div>';
            }

            $data = '<div class="list-group-item px-2">
                        <div class="row m-0 d_flex_align">
                            <input type="hidden" class="unit_name_'. $unit->id .'" value="'. $unit->name .'">
                            '. $html .'
                            '. $data2 .'
                        </div>
                    </div>';
        }
        elseif($type==2) {//detail
            $unit = \DB::table('el_unit')->where('id',$userUnit)->select(['code','name'])->first();
            $query = \DB::table('el_unit as a')->select([
                'a.id',
                'a.level',
                'a.name',
                'a.code',
                'a.parent_code',
                \DB::raw('case when b.name is not null then 1 else 0 end as child')
            ])->distinct()->leftJoin('el_unit as b','a.code','=','b.parent_code');
            $query->where('a.parent_code', $unit->code);
            $level = $level + 1;
            $units = $query->paginate(20);
            $data = $this->loadData($units, $multiple, $unitIdSelected);
            $unitId = $userUnit;
            $unitName = $unit->name;
        } elseif ($type==3){//back
            $unit = \DB::table('el_unit')->where('id', $userUnit)->first(['code', 'name', 'parent_code', 'level']);
            $model = \DB::table('el_unit')->where('code', $unit->parent_code)->first(['id', 'name', 'level']);

            $level = $model->level + 1;
            Unit::addGlobalScope(new DraftScope('level_'.$level));
            $query = Unit::leftJoin('el_unit as b','el_unit.code','=','b.parent_code');
            $query = $query->distinct()->select([
                'el_unit.id',
                'el_unit.code',
                'el_unit.name',
                'el_unit.level',
                'el_unit.parent_code',
                \DB::raw('case when b.name is not null then 1 else 0 end as child')
            ]);
            $query->where('el_unit.level', $unit->level);
            $query->where('el_unit.parent_code', $unit->parent_code);

            $units = $query->paginate(20);
            $data = $this->loadData($units, $multiple, $unitIdSelected);
            $unitId = $model->id;
            $unitName = $model->name;
        }

        return [$data, $unitId, (int) $level, $unitName];
    }

    public function searchUnitModal(Request $request) {
        $unitIdSelected = $request->unitIdSelected;
        $multiple = $request->multiple;
        $userUnit = session()->get('user_unit');
        $unitCode = Unit::find($userUnit, ['code']);
        $arrayChild = Unit::getArrayChild($unitCode->code);

        $level = $request->level;
        $search = $request->get('search', '');
        // Unit::addGlobalScope(new DraftScope());
        $query = Unit::leftJoin('el_unit as b','el_unit.code','=','b.parent_code');
        $query->select([
            'el_unit.id',
            'el_unit.code',
            'el_unit.name',
            'el_unit.level',
            'el_unit.parent_code',
            \DB::raw('case when b.name is not null then 1 else 0 end as child')
        ])->distinct();
        $query->where(function ($subQuery) use($search){
            $subQuery->orWhere('el_unit.code', 'like', '%'.$search.'%');
            $subQuery->orWhere('el_unit.name', 'like', '%'.$search.'%');
        });

        $query->WhereIn('el_unit.id', $arrayChild);
        $units = $query->paginate(20);
        $data = $this->loadData($units, $multiple, $unitIdSelected);
        return $data;
    }

    public function loadData($units, $multiple, $unitIdSelected) {
        $data = '';
        foreach ($units as $k => $unit) {
            if($unit->child) {
                $data2 = '<div class="col-1 p-0 text-center choose_unit_child">
                        <i class="fas fa-arrow-right" onclick="load_choose_unit(1, '. $unit->id .', 2, 0)"></i>
                    </div>';
            } else {
                $data2 = '';
            }

            if($multiple) {
                $units_selected = explode(',', $unitIdSelected);
                if (in_array($unit->id, $units_selected)) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $html = '<div class="col-11 cursor_pointer select_unit">
                            <input type="checkbox" id="checkbox_unit_'. $unit->id .'" onchange="checkBoxUnitHandle('. $unit->id .')" '. $checked .'>
                            <label class="m-0 cursor_pointer" for="checkbox_unit_'. $unit->id .'">
                                <span class="list-group-item-text">'. $unit->name .'</span>
                            </label>
                        </div>';
            } else {
                $html = '<div class="col-11 cursor_pointer select_unit" onclick="selectUnit('. $unit->id .')">
                            <label class="m-0 cursor_pointer">
                                <span class="list-group-item-text">'. $unit->name .'</span>
                            </label>
                        </div>';
            }

            $data .= '<div class="list-group-item px-2">
                        <div class="row m-0 d_flex_align">
                            <input type="hidden" class="unit_name_'. $unit->id .'" value="'. $unit->name .'">
                            '. $html .'
                            '. $data2 .'
                        </div>
                    </div>';
        }
        return $data;
    }
}
