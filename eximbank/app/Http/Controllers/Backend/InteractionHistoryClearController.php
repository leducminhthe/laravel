<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InteractionHistoryClear;
use App\Models\Profile;

class InteractionHistoryClearController extends Controller
{
    public function index() {
        $model = InteractionHistoryClear::orderByDesc('date_clear')->first();

        return view('backend.interaction_history_clear.index', [
            'model' => $model,
        ]);
    }

    public function getData(Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = InteractionHistoryClear::query();

        $data['total'] = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->full_name = Profile::fullname($row->user_id);
            $row->date_clear = get_date($row->date_clear);
            $row->date_created = get_date($row->created_at, 'H:i:s d/m/Y');
        }

        $data['rows'] = $rows;
        json_result(['total' => $data['total'], 'rows' => $data['rows']]);
    }

    public function save(Request $request) {
        $model = InteractionHistoryClear::firstOrNew(['id' => $request->id]);
        $model->user_id = profile()->user_id;
        $model->date_clear = date_convert($request->date_clear);

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }
}
