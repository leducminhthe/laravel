<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\InteractionHistoryName;
use Modules\Online\Entities\OnlineCourse;
use Modules\Offline\Entities\OfflineCourse;

class TrainingFormController extends Controller
{
    public function index() {
        return view('backend.category.training_form.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        TrainingForm::addGlobalScope(new DraftScope());
        $query = TrainingForm::query();
        $query->select(['el_training_form.*','b.name as training_type_name']);
        $query->from('el_training_form');
        $query->leftJoin('el_training_type as b','b.id','=','el_training_form.training_type_id');

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('name', 'like', '%'. $search .'%');
                $sub_query->orWhere('code', 'like', '%'. $search .'%');
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

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = TrainingForm::select(['id','code','name','training_type_id'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_training_form,code,'. $request->id,
            'name' => 'required',
            'training_type_id' => 'required',
        ], $request, TrainingForm::getAttributeName());

        $model = TrainingForm::firstOrNew(['id' => $request->id]);
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
        $check_online = OnlineCourse::whereIn('training_form_id', $ids)->first();
        $check_offline = OfflineCourse::whereIn('training_form_id', $ids)->first();
        if(!empty($check_online)) {
            json_message('Không thể xoá. Có dữ liệu liên quan khóa học online: '. $check_online->name, 'error');
        } else if (!empty($check_offline)) {
            json_message('Không thể xoá. Có dữ liệu liên quan khóa học offline: '. $check_offline->name, 'error');
        }
        TrainingForm::destroy($ids);
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
                $model = TrainingLocation::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = TrainingLocation::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
