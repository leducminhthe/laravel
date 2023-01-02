<?php

namespace Modules\PermissionMasterData\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\PermissionMasterData\Entities\MasterData;

class PermissionMasterDataController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){
            $search = $request->input('search');
            $sort = $request->input('sort', 'level');
            $order = $request->input('order', 'desc');
            $offset = $request->input('offset', 0);
            $limit = $request->input('limit', 20);
            $query = MasterData::query()->select('*');
            if ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->orWhere('model', 'like', '%'. $search .'%');
                    $sub->orWhere('description', 'like', '%'. $search .'%');
                });
            }
            $data['total'] = $query->count();
            $query->orderBy('model', 'asc');
            $query->offset($offset);
            $query->limit($limit);
            $rows = $query->get();
            foreach ($rows as $index => $row) {
                if($row->type==1)
                    $row->type_name = 'Tất cả';
                elseif($row->type==2)
                    $row->type_name = 'Công ty';
                elseif($row->type==3)
                    $row->type_name = 'Phân quyền';
            }
            $data['rows'] = $rows;
            json_result(['total' => $data['total'], 'rows' => $data['rows']]);
        }
        return view('permissionmasterdata::index', [
            'page_title' => 'Phân quyền master data',
        ]);
    }

    public function form(Request $request) {
        $model = MasterData::findOrFail($request->id);
        json_result([
            'model' => $model,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'model' => 'required',
            'description' => 'required',
            'type' => 'required',
        ], $request, [
            'model' => 'Tên model',
            'description' => 'Mô tả',
            'type' => 'Hình thức',
        ]);
        
        if (!\Schema::hasTable($request->model)) {
            json_message('Model không tồn tại trong database', 'error');
        } 
       
        $checkIsset = MasterData::where('model', $request->model)->first();
        if((isset($checkIsset) && !$request->id) || (isset($checkIsset) && $checkIsset->model == $request->model && !$request->id)) {
            json_message('Model đã tồn tại', 'error');
        }
       
        $model = MasterData::firstOrNew(['id' => $request->id]);
        $model->model = $request->model;
        $model->description = $request->description;
        $model->type = $request->type;
        if($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]); 
        } else {
            json_message(trans('laother.can_not_save'), 'error');
        }
    }

    public function destroy(Request $request)
    {
        $ids = $request->ids;
        MasterData::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
