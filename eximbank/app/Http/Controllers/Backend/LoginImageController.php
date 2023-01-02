<?php

namespace App\Http\Controllers\Backend;

use App\Models\LoginImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginImageController extends Controller
{
    public function index() {
        $login = LoginImage::latest()->first();
        return view('backend.login_image.index',['login' => $login]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required',
        ], $request, LoginImage::getAttributeName());

        $model = LoginImage::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->image = path_upload($request->image);

        if ($request->id) {
            $model->created_by = $model->created_by;
        } else {
            $model->created_by =profile()->user_id;
        }
        $model->updated_by = profile()->user_id;

        $save = $model->save();
        if($save)
            json_message(trans('laother.successful_save'), 'success');
        else
            json_message(trans('laother.can_not_save'), 'error');
    }

    public function form(Request $request) {
        $model = LoginImage::select(['id','status','image','type'])->where('id', $request->id)->first();
        $path_image = image_file($model->image);
        json_result([
            'model' => $model,
            'image' => $path_image
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = LoginImage::query();
        $query->where('type',1);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
            $row->image_url = image_file($row->image);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        LoginImage::destroy($ids);
        json_message(trans('laother.delete_success'));
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
                $model = LoginImage::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = LoginImage::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
