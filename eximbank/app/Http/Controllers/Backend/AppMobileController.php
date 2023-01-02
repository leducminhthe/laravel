<?php

namespace App\Http\Controllers\Backend;

use App\Models\AppMobile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic;

class AppMobileController extends Controller
{
    public function index() {
        $model_android = AppMobile::where('type', '=', 1)->first();
        $model_apple = AppMobile::where('type', '=', 2)->first();
        return view('backend.app_mobile.index',[
            'model_android' => $model_android,
            'model_apple' => $model_apple,
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'image' => 'required',
            'link' => 'required',
        ], $request, AppMobile::getAttributeName());

        if(!$request->link && !$request->file){
            json_message('Chưa chọn tệp tin hoặc liên kết', 'error');
        }

        $file = $request->file;
        if($file){
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $new_filename = \Str::slug(basename($filename, "." . $extension)) . '-' . time() . '.' . $extension;

            $storage = \Storage::disk('upload');
            $file_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);
        }

        $model = AppMobile::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->image = path_upload($request->image);
        $model->link = $request->link ?? null;
        $model->file = $request->file ? $file_path : null;
        $model->type = $request->type;
        if (empty($request->id)){
            $model->created_by = profile()->user_id;
        }
        $model->updated_by = profile()->user_id;

        $save = $model->save();
        if($save){
            $uploadPath = data_file($model->image, true, 'upload');
            $resize_image = ImageManagerStatic::make($uploadPath);
            $resize_image->resize(132, 42);
            $resize_image->save($uploadPath);

            json_message(trans('laother.successful_save'), 'success');
        }else{
            json_message(trans('laother.can_not_save'), 'error');
        }
    }
}
