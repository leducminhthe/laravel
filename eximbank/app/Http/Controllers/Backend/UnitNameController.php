<?php

namespace App\Http\Controllers\Backend;

use App\Exports\UnitExport;
use App\Imports\UnitImport;
use App\Models\AreaName;
use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Models\Categories\UnitManager;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\UnitName;
use App\Scopes\DraftScope;
use App\Models\UnitView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyUnitOfCompletedImportUnit;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Models\Notifications;
use App\Imports\UnitImportUpdate;

class UnitNameController extends Controller
{
    public function index(Request $request) {
        $errors = session()->get('errors');
        \Session::forget('errors');
        $page_title =  trans('lacategory.unit_structure') ;
        if ($request->ajax()){
            $search = $request->input('search');
            $sort = $request->input('sort', 'level');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);
            $query = UnitName::query();
            $query->select([ '*' ])->from('el_unit_name');
            if ($search) {
                $query->where('name', 'like', '%'. $search .'%');
            }
            $data['total'] = $query->count();
            $query->orderBy('level', 'asc');
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();
            $data['rows'] = $rows;
            json_result(['total' => $data['total'], 'rows' => $data['rows']]);
        }
        return view('backend.category.unit.unit_name.index', [
            'page_title' => $page_title,
        ]);
    }

    public function form(Request $request) {
        $model = UnitName::findOrFail($request->id);
        json_result([
            'model' => $model,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'name_en' => 'required',
            'level' => 'required|integer|min:0',
        ], $request, UnitName::getAttributeName());

        $checkLevel = UnitName::where('level', $request->level)->exists();
        if($checkLevel) {
            json_message('Cấp độ đã tồn tại', 'error');
        }
       
        $model = UnitName::firstOrNew(['id' => $request->id]);
        $model->name = $request->name;
        $model->name_en = $request->name_en;
        $model->level = $request->level;
        $model->description = $request->description;
        if($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]); 
        } else {
            json_message(trans('laother.can_not_save'), 'error');
        }
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $key => $id) {
            $model = UnitName::find($id);
            $check = Unit::where('level', $model->level)->exists();
            if ($check) {
                json_message('Không thể xóa: '.$model->name.' vì đã có đơn vị tồn tại', 'error');
            } else {
                $model->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
