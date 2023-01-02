<?php

namespace App\Http\Controllers\FileManager;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\WarehouseFolder;

class LfmController extends Controller
{
    use LfmHelpers;
    protected static $success_response = 'OK';
    protected static $error_response = 'FALSE';

    public function show() {
        $url_previous = url()->previous();
        $url_format = explode('/', $url_previous);
        if($url_format[4] == 'libraries' || $url_format[4] == 'online' || $url_format[4] == 'offline' || $url_format[4] == 'category' || $url_format[4] == 'course-plan') {
            session(['url_filemanager_child' => $url_format[5]]);
            session()->save();
        }
        if($url_format[3] != 'admin-cp' && $url_format[4] != 'thread') {
            session(['url_filemanager' => $url_format[3]]);
        } else {
            session(['url_filemanager' => $url_format[4]]);
        }
        session()->save();
        
        $type = $this->currentLfmType();
        $mimetypes = config('lfm.storage.'. $type .'.mimetypes');
        $max_file_size = config('lfm.storage.'. $type .'.max_file_size');

        return view('file-manager.index', [
            'mimetypes' => $mimetypes,
            'max_file_size' => $max_file_size,
        ]);
    }

    public function getPath($working_dir = null) {
        $type = $this->currentLfmType();
        if(empty($working_dir)) {
            if(session()->get('url_filemanager') == 'libraries' || session()->get('url_filemanager') == 'category' || session()->get('url_filemanager') == 'course-plan') {
                $folder = WarehouseFolder::where('name_url', session()->get('url_filemanager_child'))->where('type', $type)->first();
            } else if (session()->get('url_filemanager') == 'online' && (session()->get('url_filemanager_child') == 'libraryFile' || (session()->get('url_filemanager_child') == 'activity-lesson' && $type == 'image'))) {
                $folder = WarehouseFolder::where('name_url', session()->get('url_filemanager_child'))->where('type', $type)->first();
            } else if (session()->get('url_filemanager') == 'offline' && session()->get('url_filemanager_child') == 'upload') {
                $folder = WarehouseFolder::where('name_url', session()->get('url_filemanager_child'))->where('type', $type)->first();
            } else if (session()->get('url_filemanager') == 'quiz' || session()->get('url_filemanager') == 'quiz-template') {
                $folder = WarehouseFolder::where('name_url', 'quiz')->where('type', $type)->first();
            } else {
                
                $folder = WarehouseFolder::where('name_url', session()->get('url_filemanager'))->where('type', $type)->first();
            }
            $path = $folder->id;
        } else {
            $path = $working_dir;
        }

        return $path;
    }

    public function getErrors() {
        $arr_errors = [];

        if (!extension_loaded('gd') && !extension_loaded('imagick')) {
            array_push($arr_errors, trans('lfm.message-extension_not_found'));
        }

        return $arr_errors;
    }

    protected function currentLfmType() {
        $type = request()->get('type');
        $type = strtolower($type);
        
        switch ($type) {
            case 'image': return 'image';
            case 'images': return 'image';
            case 'file': return 'file';
            case 'files': return 'file';
            case 'scorm': return 'scorm';
            case 'xapi': return 'xapi';
            default: return 'image';
        }
    }

    protected function error($error_type, $variables = [])
    {
        return trans('lfm.error-' . $error_type, $variables);
    }
//    public function translateFromUtf8($input)
//    {
//        if ($this->isRunningOnWindows()) {
//            $input = iconv('UTF-8', mb_detect_encoding($input), $input);
//        }
//
//        return $input;
//    }
//    public function isRunningOnWindows()
//    {
//        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
//    }

    public function downloadItem($id) {
        $disk = 'local';
        $item = Warehouse::find($id);
        return redirect()->to(link_download('uploads/'. $item->file_path));
    }
}
