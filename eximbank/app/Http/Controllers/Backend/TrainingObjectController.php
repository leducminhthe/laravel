<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\TrainingObject;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Online\Entities\OnlineCourse;

class TrainingObjectController extends Controller
{
    public function index() {
        \Session::forget('errors');
        return view('backend.category.training_object.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = TrainingObject::query();
        $query->select([
            '*'
        ]);

        if ($search) {
            $query->where(function ($subquery) use ($search){
                $subquery->orWhere('name', 'like', '%'. $search .'%');
                $subquery->orWhere('code', 'like', '%'. $search .'%');
            });
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

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function form(Request $request) {
        $model = TrainingObject::select(['id','status','code','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_training_object,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ], $request, TrainingObject::getAttributeName());

        $model = TrainingObject::firstOrNew(['id' => $request->id]);
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
        foreach ($ids as $key => $id) {
            $check_online = OnlineCourse::Where('training_object_id', 'like', '%'. $id .'%')->first(['name']);
            $check_offline = OfflineCourse::Where('training_object_id', 'like', '%'. $id .'%')->first(['name']);
            if(!empty($check_online)) {
                json_message('Không thể xoá. Có dữ liệu liên quan khóa học online: '. $check_online->name, 'error');
            } else if (!empty($check_offline)) {
                json_message('Không thể xoá. Có dữ liệu liên quan khóa học offline: '. $check_offline->name, 'error');
            }
        }        
        TrainingObject::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
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
                $model = TrainingObject::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = TrainingObject::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
