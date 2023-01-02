<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Profile;

class CertController extends Controller
{
    public function index() {
        return view('backend.category.cert.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        Certificate::addGlobalScope(new DraftScope());
        $query = Certificate::query();

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->orWhere('certificate_code', 'like', '%'. $search .'%');
                $sub_query->orWhere('certificate_name', 'like', '%'. $search .'%');
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
        $model = Certificate::select(['id','certificate_code','certificate_name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'certificate_code' => 'required',
            'certificate_name' => 'required',
        ], $request, Certificate::getAttributeName());

        $model = Certificate::find($request->id);
        if ($model){
            $model->certificate_code = $request->input('certificate_code');
            $model->certificate_name = $request->input('certificate_name');
            $model->updated_by = profile()->user_id;
            if ($request->id) {
                $model->created_by = $model->created_by;
            }
            $model->save();

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.category.cert.edit', [
                    'id' => $model->id
                ])
            ]);
        }

        $exists = Certificate::where('certificate_code', '=', $request->input('certificate_code'))->exists();

        if ($exists){
            json_message('Mã trình độ đã tồn tại', 'error');
        }

        $model = new Certificate();
        $model->certificate_code = $request->input('certificate_code');
        $model->certificate_name = $request->input('certificate_name');
        $model->updated_by = profile()->user_id;
        $model->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        foreach ($ids as $key => $id) {
            $code = Certificate::find($id, ['certificate_code']);
            $check = Profile::where('certificate_code', $code->certificate_code)->first(['firstname']);
            if (isset($check)) {
                json_message('Không thể xóa vì có user: '.$check->firstname.' đang sử dụng', 'error');
            }
            Certificate::find($id)->delete();
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
