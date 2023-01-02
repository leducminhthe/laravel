<?php

namespace App\Http\Controllers\Backend;

use App\Imports\TitlesImport;
use App\Exports\TitlesExport;
use App\Models\Categories\Position;
use App\Models\Categories\Unit;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use App\Models\Categories\TitleRank;
use Modules\Capabilities\Entities\CapabilitiesTitle;
use App\Models\Profile;
use Modules\CareerRoadmap\Entities\CareerRoadmap;
use App\Models\Categories\UnitType;
use Spatie\SimpleExcel\SimpleExcelWriter;

class TitlesController extends Controller
{
    public function index() {
        $title_ranks = TitleRank::where('status',1)->get();
        $units_type = UnitType::get();
        \Session::forget('errors');

        Titles::addGlobalScope(new DraftScope());
        $total_model = Titles::count();
        $total_model_active = Titles::where('status', 1)->count();

        return view('backend.category.titles.index',[
            'title_ranks' => $title_ranks,
            'units_type' => $units_type,
            'total_model' => $total_model,
            'total_model_active' => $total_model_active,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $group = $request->input('group');
        $unit = $request->input('unit_id');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        Titles::addGlobalScope(new DraftScope());
        $query = Titles::query();
        $query->select([
            'el_titles.id',
            'el_titles.code',
            'el_titles.name',
            'el_titles.status',
            'el_titles.created_by',
            'el_titles.updated_by',
            'unit.name AS unit_name',
            'unit.level AS unit_level',
            'unit.code AS unit_code',
            'tr.name as title_rank_name',
            'ut.name as unit_type_name',
        ]);
        $query->leftJoin('el_unit AS unit', 'unit.id', '=', 'el_titles.unit_id');
        $query->leftJoin('el_title_rank AS tr', 'tr.id', '=', 'el_titles.group');
        $query->leftJoin('el_unit_type AS ut', 'ut.id', '=', 'el_titles.unit_type');

        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('el_titles.name', 'like', '%'. $search .'%');
                $subquery->orWhere('el_titles.code', 'like', '%'. $search .'%');
            });
        }

        if ($group) {
            $query->where('group', '=', $group);
        }

        if ($unit) {
            $unit = explode(';', $unit);
            $query->whereIn('unit_id', $unit);
        }

        $count = $query->count();
        $query->orderBy('el_titles.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.titles.edit', ['id' => $row->id]);
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
            $row->kpi = route('backend.category.title.kpi',['id' => $row->id]);
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function form(Request $request) {
        $model = Titles::findOrFail($request->id);
        $unit_code = @Unit::find($model->unit_id)->code;
        $unit = Unit::getTreeParentUnit($unit_code);
        $position = Position::find($model->position_id);
        $title_rank = TitleRank::find($model->group);
        if($title_rank->status==0) $model->hgroup = $title_rank->name . " (Đã tắt)";
        else $model->hgroup ="";
       // dd($unit);
        json_result([
            'model' => $model,
            'unit' => $unit,
            'position' => $position,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_titles,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
            'group' => 'required',
            'unit_id' => 'nullable|exists:el_unit,id'
        ], $request, Titles::getAttributeName());
        $model = Titles::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->position_id = $request->position_id;
        $model->unit_type = $request->unit_type;
        $model->created_by = $model->created_by ? $model->created_by : profile()->user_id;
        if ($model->unit_id) {
            $model->unit_level = Unit::find($model->unit_id)->level;
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach($ids as $id) {
            $checkTitleCareerRoadmap = CareerRoadmap::where('title_id','=',$id)->get();
            $checkTitleProfile = Profile::where('title_id','=',$id)->get();
            if ( !$checkTitleProfile->isEmpty() || !$checkTitleCareerRoadmap->isEmpty()) {
                json_result([
                    'status' => 'error',
                    'message' => 'Không thể xóa vì có liên quan đến người dùng hoặc lộ trình',
                ]);
            } else {
                Titles::find($id)->delete();
            }
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new TitlesImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('backend.category.titles'),
        ]);
    }

    public function export()
    {
        return (new TitlesExport())->download('danh_sach_chuc_danh_'. date('d_m_Y') .'.xlsx');
    }

    public function export_simple()
    {
        $rows = [];
        Titles::chunk(200, function($titles) use (&$rows) {
            foreach ($titles->toArray() as $key => $title) {
                $rows[] = $title;
            }
        });
        SimpleExcelWriter::streamDownload('test.xlsx')
        ->noHeaderRow()
        ->addRows($rows);
    }

    public function ajaxIsopenPublish(Request $request) {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => 'Chức danh',
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $model = Titles::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = Titles::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function getKpi($id, Request $request){
        $title = Titles::find($id, ['title_time_kpi', 'user_time_kpi']);
        return view('backend.category.titles.modal_kpi', [
            'title' => $title,
        ]);
    }
}
