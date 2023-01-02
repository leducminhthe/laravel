<?php

namespace App\Http\Controllers\Backend;

use App\Models\DashboardByUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardByUserController extends Controller
{
    public function index() {
        return view('backend.dashboard_by_user.index');
    }

    public function getData(Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = DashboardByUser::query()
            ->select([
                'id',
                'name',
            ]);

        $data['total'] = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
        }

        $data['rows'] = $rows;
        json_result(['total' => $data['total'], 'rows' => $data['rows']]);
    }

    public function form(Request $request) {
        $model = DashboardByUser::findOrFail($request->id);

        json_result([
            'model' => $model,
        ]);
    }

    public function save(Request $request) {
        $model = DashboardByUser::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        if ($request->condition_online_complete){
            $model->condition = $request->condition_online_complete;
        }
        if ($request->condition_top_in_unit){
            $model->condition = $request->condition_top_in_unit;
        }
        if ($request->condition_top_user){
            $model->condition = $request->condition_top_user;
        }

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }
}
