<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\District;
use App\Models\Categories\Province;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingLocation;

class TrainingLocationController extends Controller
{
    public function index() {
        $province = Province::all();
        $district = District::get();
        return view('backend.category.training_location.index',[
            'province' => $province,
            'district'=> $district
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        TrainingLocation::addGlobalScope(new DraftScope());
        $query = TrainingLocation::query()
            ->leftJoin('el_province as b','el_training_location.province_id','=','b.code')
            ->leftJoin('el_district as c','el_training_location.district_id','=','c.id')
            ->select(['el_training_location.*','b.name as province','c.name as district']);
        if ($search) {
            $query->orWhere('el_training_location.code', 'like', '%'. $search .'%');
            $query->orWhere('el_training_location.name', 'like', '%'. $search .'%');
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
        $model = TrainingLocation::select(['id','status','code','name','district_id','province_id'])->where('id', $request->id)->first();
        $province = Province::select(['id','name'])->where('id', $model->province_id)->first();
        $districts = District::select(['id','name'])->where('province_id', $province->id)->get();
        json_result([
            'model' => $model,
            'province' => $province,
            'districts' => $districts,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_training_location,code,'. $request->id,
            'name' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'status' => 'required|in:0,1'
        ], $request, TrainingLocation::getAttributeName());
        
        $model = TrainingLocation::firstOrNew(['id' => $request->id]);
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
        TrainingLocation::destroy($ids);
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
