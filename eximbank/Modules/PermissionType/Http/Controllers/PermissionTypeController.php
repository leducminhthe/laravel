<?php

namespace Modules\PermissionType\Http\Controllers;

use App\Models\Categories\Unit;
use App\Models\PermissionType;
use App\Models\PermissionTypeUnit;
use App\Models\Profile;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class PermissionTypeController extends Controller
{
    public function index() {
        return view('permissiontype::backend.index',[
        ]);
    }
    public function getData( Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        PermissionType::addGlobalScope(new DraftScope());
        $query = PermissionType::query()->select(['*']);
        $query->where('type', '!=', 1);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row){
            if ($row->type == 1){
                $row->created_by = trans('backend.default');
                $row->updated_by = trans('backend.default');
            }else{
                $created_by = Profile::find($row->created_by);
                $updated_by = Profile::find($row->updated_by);

                $row->created_by = ($created_by->lastname . ' ' . $created_by->firstname);
                $row->updated_by = ($updated_by->lastname . ' ' . $updated_by->firstname);
            }

            $row->permission_edit = userCan('permission-group-edit');
            $row->permission_delete = userCan('permission-group-delete');

        }
        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function showModal(Request $request) {
        $model = PermissionType::firstOrNew(['id' => $request->id]);
        return view('permissiontype::modal.add_permission_type', [
            'model' => $model,
        ]);
    }

    public function loadUnits(Request $request)
    {
        $permission_type = $request->id;
        $query = Unit::query();
        $query->select([
            'a.id',
            'a.code',
            'a.name',
            'a.level',
            'a.parent_code',
        ])->disableCache();
        $query->from('el_unit as a');
        $query->leftjoin('el_permission_type_unit as b', function($join) use ($permission_type) {
            $join->on('b.unit_id', '=', 'a.id');
            $join->where('b.permission_type_id', $permission_type);
        });
        $query->orderBy('b.unit_id', 'desc');
        $query->orderBy('a.id', 'asc');
        $units = $query->paginate(20);
        $data = '';
        $data = $this->loadData($units, $permission_type);
        return $data;
    }

    public function searchUnits(Request $request)
    {
        $permission_type = $request->get('id', 0);
        $search = $request->get('search', '');
        $query = Unit::query();
        $query->select([
            'a.id',
            'a.code',
            'a.name',
            'a.level',
            'a.parent_code',
        ]);
        $query->from('el_unit as a');
        $query->where('a.name', 'like', '%'. $search .'%');
        $units = $query->get();
        $data = '';
        $data = $this->loadData($units, $permission_type);
        return $data;
    }

    public function loadData($units, $permission_type) {
        $data = '';
        foreach ($units as $k => $v) {
            $check = PermissionTypeUnit::where('unit_id', $v->id)->where('permission_type_id', '=', $permission_type)->first();

            $data .= '<div class="list-group-item">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="m-0">
                                    <input type="checkbox" name="unit[]" '. (isset($check) ? "checked" : "") .' value="'. $v->id .'">
                                    <span class="list-group-item-text"><i class="fa fa-fw"></i> '. $v->name .'</span>
                                </label>
                            </div>
                            <div class="col-md-5 radio--group-inline-container">
                                <label class="m-0">
                                    <input type="radio" name="type['. $v->id .']" value="owner" '. (isset($check) && $check->type == "owner" ? "checked" : '') .'>
                                    Owner
                                </label>
                                <label class="m-0">
                                    <input type="radio" name="type['. $v->id .']" value="group-child" '. (isset($check) && $check->type == 'group-child' ? "checked" : '') .'>
                                    Group-child
                                </label>
                            </div>
                        </div>
                    </div>';
        }
        return $data;
    }

    public function save(Request $request) {
        $this->validateRequest([
            'id' => 'nullable|exists:el_permission_type,id',
            'name' => 'required|string|unique:el_permission_type,name,'.$request->id
        ], $request, PermissionType::getAttributeName());

        $validator = \Validator::make($request->all(),
            [
                'unit'=>'required',
                'type'=>'required',
            ],
            [
                'unit.*'=>'Chưa chọn đơn vị',
                'type.*'=>'Chưa chọn loại',
            ]
        );
        $units = $request->unit;
        $type = $request->type;
        foreach ($units as $index => $item) {
            if ($item && !isset($type[$item]))
                json_message('Chưa chọn loại quyền', 'error');
        }
        if($validator->fails()){
            json_message($validator->errors()->all()[0], 'error');
        }

        $max_sort = PermissionType::orderByDesc('sort')->first();

        $model = PermissionType::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if (empty($request->id)) {
            $model->created_by = profile()->user_id;
        }
        $model->updated_by = profile()->user_id;
        $model->type = 2;
        $model->sort = $request->id ? $model->sort : ($max_sort->sort + 1);
        if ($model->save()) {
            foreach ($units as $value){
                $data[] = ['permission_type_id'=>$model->id,'unit_id'=>$value,'type'=>$type[$value]];
            }
            PermissionTypeUnit::query()->where('permission_type_id','=',$model->id)->delete();
            PermissionTypeUnit::query()->insert($data);
            $this->clearCachePermission();
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.permission.type')
            ]);
        }

        json_message('Không thể lưu dữ liệu', 'error');
    }
    public function delete(Request $request) {
        PermissionType::where('type','=',2)->where('id','=',$request->ids[0])->delete();
        PermissionTypeUnit::where('permission_type_id',$request->ids[0])->delete();
        $this->clearCachePermission();
        json_message(trans('laother.delete_success'));
    }
    private function clearCachePermission(){
        Artisan::call('modelCache:clear', ['--model' => PermissionType::class]);
        Artisan::call('modelCache:clear', ['--model' => PermissionTypeUnit::class]);
    }
}
