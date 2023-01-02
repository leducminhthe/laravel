<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TypeCost;
use App\Models\Categories\TrainingCost;

class TypeCostController extends Controller
{
    public function index() {
        return view('backend.category.type_cost.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = TypeCost::query();
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.type_cost.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = TypeCost::where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
            'code' => 'required',
            'type' => 'required',
        ], $request, TypeCost::getAttributeName());

        $model = TypeCost::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

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
        foreach ($ids as $key => $id) {
            $check_use_type_cost = TrainingCost::where('type','=',$id)->first();
            if (!empty($check_use_type_cost)) {
                json_result([
                    'status' => 'error',
                    'message' => 'Không thể xóa vì có chi phí đào tạo đang sử dụng loại chi phí này',
                    'redirect' => route('backend.category.type_cost')
                ]);
            } else {
                TypeCost::find($id)->delete();
                json_result([
                    'status' => 'success',
                    'message' => trans('laother.delete_success'),
                ]);
            }
        }
    }
}
