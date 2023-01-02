<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {
        return view('backend.setting.index');
    }

    public function closeOpendMenu(Request $request){
        session(['close_open_menu_backend' => $request->status]);
        session()->save();
    }

    public function cache(){
        return view('backend.cache.index');
    }

    public function clearCache(){
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');

        session(['success' => trans('laother.delete_success')]);
        session()->save();

        return redirect()->route('backend.cache');
    }

    public function updateSource(){
        return view('backend.update_source.index');
    }

    public function saveUpdateSource(Request $request){
        $path = path_upload($request->post('path'));

        $zip = new \ZipArchive();
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $today = date('Y/m/d');

        if (!$storage->exists($path)) {
            json_message('File không tồn tại', 'error');
        }

        $res = $zip->open($storage->path($path));
        if ($res === true) {
            $unzip_folder = $today.'/update_source';
            $folder_source = $storage->path($unzip_folder);

            if (!$storage->exists($unzip_folder)) {
                \File::makeDirectory($folder_source, 0777, true);
            }

            $zip->extractTo($folder_source);
            $zip->close();

            $files = \File::allFiles($folder_source);
            foreach ($files as $file) {
                try {
                    $file_path = $file->getRelativePathname();

                    if(config('app.app1_path')){
                        $newFile1 = config('app.app1_path').'/'.$file_path;
                        \File::copy($file->getPathName(), $newFile1);
                    }

                    if(config('app.app2_path')){
                        $newFile2 = config('app.app2_path').'/'.$file_path;
                        \File::copy($file->getPathName(), $newFile2);
                    }
                } catch (\Exception $e) {
                    return $e->getMessage();
                }
            }

            \File::deleteDirectory($folder_source);
            \File::delete($storage->path($path));

            Warehouse::whereFilePath($path)->where('created_by', 1)->delete();

            json_result([
                'status' => 'success',
                'message' => trans('laother.update_successful'),
                'redirect' => route('backend.update_source')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => 'Không thể cập nhật',
            'redirect' => route('backend.update_source')
        ]);
    }

    public function zoomScreen(Request $request){
        session(['zoom_screen' => $request->status]);
        session()->save();
    }
}
