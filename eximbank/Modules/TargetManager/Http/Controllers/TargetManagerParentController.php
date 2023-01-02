<?php

namespace Modules\TargetManager\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\TargetManager\Entities\TargetManagerParent;

class TargetManagerParentController extends Controller
{
    public function index()
    {
        return view('targetmanager::backend.target_manager_parent.index',[
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = TargetManagerParent::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->group_object_url = route('module.target_manager', ['parent_id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'year' => 'required',
        ], $request, TargetManagerParent::getAttributeName());

        if(empty($request->id)){
            $check = TargetManagerParent::where('year', $request->year);
            if ($check->exists()) {
                json_message('Quản lý chỉ tiêu theo năm '. $request->year .' đã tồn tại', 'error');
            }
        }

        $model = TargetManagerParent::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function form(Request $request) {
        $model = TargetManagerParent::select(['id', 'name', 'year'])->where('id', $request->id)->first();
        json_result([
            'model' => $model
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);

        TargetManagerParent::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
