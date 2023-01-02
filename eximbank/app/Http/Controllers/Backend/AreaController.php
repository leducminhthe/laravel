<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Imports\AreaImport;
use App\Jobs\NotifyAreaOfCompletedImportUnit;
use Illuminate\Support\Str;
use App\Models\AreaName;
use App\Scopes\DraftScope;

class AreaController extends Controller
{
    public function index($level) {
        \Session::forget('errors');
        $parent_area = Area::getAreaParent($level);
        $units3 = Unit::select(['id','name','code'])->where('level',3)->where('status',1)->get();

        $areaName = AreaName::where('level', $level)->first();
        $page_title = \App::getLocale() == 'vi' ? $areaName->name : $areaName->name_en;
        return view('backend.category.area.index', [
            'level' => $level,
            'parent_area' => $parent_area,
            'units3' => $units3,
            'page_title' => $page_title
        ]);
    }

    public function getData($level, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'el_area.id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        Area::addGlobalScope(new DraftScope());
        $query = Area::query();
        $query->select([
            'el_area.*',
            'b.name AS parent_name'
        ])->disableCache();
        $query->from('el_area');
        $query->leftJoin('el_area AS b', 'b.code', '=', 'el_area.parent_code');
        $query->where('el_area.level', '=', $level);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_area.code', 'like', '%'. $search .'%');
                $subquery->orWhere('el_area.name', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy('el_area.id', $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.area.edit', ['level' => $level, 'id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($level, Request $request) {
        $model = Area::select(['id','status','code','name','parent_code','unit_id'])->where('id', $request->id)->first();
        $parent = Area::select('id')->where('code', '=', $model->parent_code)->first();
        json_result([
            'model' => $model,
            'parent' => $parent
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_area,code,'. $request->id,
            'name' => 'required',
            'level' => 'required|integer|min:0',
            'parent_id' => 'nullable|exists:el_area,id',
            'status' => 'required|in:0,1',
        ], $request, Area::getAttributeName());

        
        $parent = Area::findOrNew($request->parent_id);
        $model = Area::firstOrNew(['id' => $request->id]);
        $model->parent_code = $parent->code;
        $model->fill($request->all());

        if ($model->save()) {
            if ($model->save()) {
                json_result([
                    'status' => 'success',
                    'message' => trans('laother.successful_save'),
                ]);
            }
        }
        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        Area::deleteArray($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $area_id = $request->area_id;
        $file = $request->file('import_file');

        $import = new AreaImport(\Auth::user());
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        } else {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.category.area',['level' => $area_id])
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => trans('laother.unable_upload'),
            'redirect' => route('backend.category.area',['level' => $area_id])
        ]);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('laprofile.rank'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Area::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Area::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
