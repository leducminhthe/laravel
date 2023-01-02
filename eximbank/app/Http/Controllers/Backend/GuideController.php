<?php

namespace App\Http\Controllers\Backend;

use App\Models\Guide;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class GuideController extends Controller
{
    public function index() {
        return view('backend.guide.index');
    }

    public function form($id = null) {
        $model = Guide::firstOrNew(['id' => $id]);
        $page_title = $model->id ? $model->name : trans('labutton.add_new');
        return view('backend.guide.form', [
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->get('search');
        $type = $request->get('type');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        Guide::addGlobalScope(new DraftScope());
        $query = Guide::query();
        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        if ($type) {
            $query->where('type', $type);
        }
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            if ($row->type != 3){
                $row->attach = basename($row->attach);
            }
            $row->edit_url = route('backend.guide.edit', ['id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function remove(Request $request) {
        $ids = $request->post('ids', []);
        Guide::destroy($ids);
        json_message(trans('laother.delete_success'));
    }

    public function save(Request $request) {
        $this->validateRequest([
            'name' => 'required_if:id,',
            'type' => 'required',
            'attach' => 'required_if:type,1|',
            'content' => 'required_if:type,3|',
        ], $request, [
            'type' => 'Thể loại',
            'name' => 'Tên hướng dẫn',
            'attach' => 'File hướng dẫn',
            'content' => trans("lamenu.post"),
        ]);
        $type = $request->type;
        $flag = $request->flag;

        if ($type == 2 && $flag == 0) {
            $this->validateRequest([
                'video' => 'required|mimes:mp4,x-flv,x-mpegURL,MP2T,3gpp,quicktime,x-msvideo,x-ms-wmv',
            ], $request);
            $file = $request->video;
            $type_file = 'file';
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) . '-' . time() . '-' . Str::random(10) . '.' . $extension;
            $storage = \Storage::disk('upload');
            $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);
            $content = $new_path;
        } else if ($type == 3) {
            $content = $request->get('content');
        } else {
            $content = $request->content_of_id;
        }

        $model = Guide::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->type = $type;
        if ($type == 2 || $type == 3) {
            $model->attach = $content;
        } else {
            $model->attach = path_upload($model->attach);
        }


        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.guide')
            ]);
        }
        json_message(trans('laother.can_not_save'), 'error');
    }
}
