<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingCost;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Offline\Entities\OfflineCourseCost;
use App\Models\TypeCost;

class TrainingCostController extends Controller
{
    public function index() {
        $get_type_costs = TypeCost::get();
        return view('backend.category.training_cost.index',[
            'type_costs' => $get_type_costs,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        TrainingCost::addGlobalScope(new DraftScope());
        $query = TrainingCost::query();
        $query->select('el_training_cost.*', 'b.name as type_cost_name');
        $query->from('el_training_cost');
        $query->leftjoin('el_type_cost as b','b.id','=','el_training_cost.type');

        if ($search) {
            $query->where('el_training_cost.name', 'like', '%'. $search .'%');
            $query->orWhere('b.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('type');
        $query->orderBy($sort);
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
        $model = TrainingCost::select(['id','type','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, TrainingCost::getAttributeName());

        $model = TrainingCost::firstOrNew(['id' => $request->id]);
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
        $costOfOnline = OnlineCourseCost::query()->whereIn('cost_id',$ids);
        $costOfOffline = OfflineCourseCost::query()->whereIn('cost_id',$ids);
        if ($costOfOnline->exists() || $costOfOffline->exists()) {
            json_result([
                'status' => 'error',
                'message' => 'Không thể xóa vì có khóa học đang sử dụng chi phí này',
            ]);
        } else {
            TrainingCost::destroy($ids);
            json_result([
                'status' => 'success',
                'message' => trans('laother.delete_success'),
            ]);
        }
    }
}
