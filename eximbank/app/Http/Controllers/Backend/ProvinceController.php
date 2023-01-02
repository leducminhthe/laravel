<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\District;
use App\Imports\ProviceImport;
use App\Models\Categories\Province;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitType;
use App\Models\Categories\UnitManager;
use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use User\Acl\Rule;

class ProvinceController extends Controller
{
    public function index() {
        \Session::forget('errors');
        return view('backend.category.province.index');
    }

    public function getData( Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'name');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Province::addGlobalScope(new DraftScope());
        $query = Province::query();
        $query->select(['*']);
        $query->from('el_province');

        if ($search) {
            $query->orWhere('id', 'like', '%'. $search .'%');
            $query->orWhere('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = Province::select(['id','code','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|integer|min:1|max:500',
            'name' => 'required|max:250',
        ], $request, Province::getAttributeName());

        $model = Province::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = profile()->user_id;
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

        $related = District::whereIn('province_id', $ids)->first();
        $related1 = TrainingLocation::whereIn('province_id', $ids)->first();
        if ($related || $related1){
            json_message(trans('laother.related_data'). '. ' .trans('laother.can_not_delete'), 'error');
        }

        Province::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ProviceImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }
        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('backend.category.province'),
        ]);
    }
}
