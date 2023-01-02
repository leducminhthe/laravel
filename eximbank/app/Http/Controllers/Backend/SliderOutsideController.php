<?php

namespace App\Http\Controllers\Backend;

use App\Models\SliderOutside;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SliderOutsideController extends Controller
{
    public function index() {
        return view('backend.slider_outside.index');
    }

    public function form($id = null) {
        $model = SliderOutside::firstOrNew(['id' => $id]);

        $page_title = $id ? trans('lasetting.extenal_banner').' '. $id : trans('lasetting.add_new');
        return view('backend.slider_outside.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function getData(Request $request) {
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = SliderOutside::query();

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('backend.slider_outside.edit', ['id' => $row->id]);
            $row->image_url = image_file($row->image);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        SliderOutside::destroy($ids);
        json_message(trans('laother.delete_success'));
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required_if:id,',
        ], $request, [
            'image' => trans("latraining.picture"),
            'status' => trans("latraining.status")
        ]);

        $model = SliderOutside::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $sizes = config('image.sizes.slide');
        $model->image = upload_image($sizes, $request->image);

        if (empty($model->id)) {
            $model->created_by = profile()->user_id;
        }
        $model->updated_by = profile()->user_id;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.slider_outside')
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
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
                $model = SliderOutside::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = SliderOutside::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
