<?php

namespace App\Http\Controllers\FileManager;

use App\Models\Warehouse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Http\UploadedFile;
use App\Models\Profile;
use Modules\Role\Entities\TitleRole;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class UploadController extends LfmController
{
    protected $errors;

    public function __construct() {
        //parent::__construct();
        $this->errors = [];
    }

    public function upload(Request $request)
    {
        $folder_id = $request->input('working_dir');
        $type = $this->currentLfmType();
        
        if (empty($folder_id)) {
            $folder_id = null;
        }

        $error_bag = [];
    
        try {
            $receiver = new FileReceiver('upload', $request, HandlerFactory::classFromRequest($request));
            if ($receiver->isUploaded() === false) {
                throw new UploadMissingFileException();
            }

            $save = $receiver->receive();
            $file = $save->getFile();
            $fileName = $file->getClientOriginalName();
            $check = Warehouse::where('folder_id', $folder_id)->where('file_name', $fileName)->exists();
            if($check) {
                json_message('File đã tồn tại', 'error');
            }
            
            if ($save->isFinished()) {
                if(in_array($folder_id, [23,24,30]) && $type == 'image') {
                    $save_detail = 1;
                    $save_file = $this->saveFile($save->getFile(), $type, $folder_id, $save_detail);
                } else {
                    $save_file = $this->saveFile($save->getFile(), $type, $folder_id);
                }
                if ($save_file) {
                    return $this->response($this->errors);
                }
                return $this->response($this->errors);
            }

            $handler = $save->handler();

            return response()->json([
                "done" => $handler->getPercentageDone(),
                'status' => true
            ]);

        } catch (\Exception $e) {
            \Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            array_push($error_bag, $e->getMessage());
            return $this->response($error_bag);
        }
    }

    protected function saveFile(UploadedFile $file, $type, $folder_id, $save_detail = null) {
        $filename = $this->createFilename($file);
        $storage = \Storage::disk('upload');
        $new_path = $storage->putFileAs(date('Y/m/d'), $file, $filename);

        if (!$this->fileIsValid($storage->path($new_path))) {
            unlink($storage->path($new_path));
            return false;
        }

        if ($new_path) {
            $admin = Auth::user()->isAdmin();
            $profile = profile();
            $check_user_role = Profile::query()
                ->from('el_profile as a')
                ->join('el_model_has_roles as ur','ur.model_id','=','a.user_id')
                ->where('user_id', profile()->user_id)
                ->first();
            $check_title_role = TitleRole::where('title_id',$profile->title_id)->first();

            $warehouse = new Warehouse();
            $warehouse->file_name = $file->getClientOriginalName();
            $warehouse->file_type = $file->getMimeType();
            $warehouse->file_path = $new_path;
            $warehouse->file_size = $file->getSize();
            $warehouse->extension = $file->getClientOriginalExtension();
            $warehouse->source = 'upload';
            $warehouse->type = $type;
            if(!$admin && empty($check_user_role) && empty($check_title_role)) {
                $warehouse->check_role = 0;
            }
           //$warehouse->user_id = profile()->user_id;
            $warehouse->folder_id = $folder_id;
            $warehouse->save();
        }

        if($save_detail) {
            $new_path_detail = $storage->putFileAs('detail/'. date('Y/m/d'), $file, $filename);
            if ($new_path_detail) {
                $warehouse = new Warehouse();
                $warehouse->file_name = $file->getClientOriginalName();
                $warehouse->file_type = $file->getMimeType();
                $warehouse->file_path = $new_path_detail;
                $warehouse->file_size = $file->getSize();
                $warehouse->extension = $file->getClientOriginalExtension();
                $warehouse->source = 'upload';
                $warehouse->type = $type;
                $warehouse->folder_id = 0;
                $warehouse->save();
            }
        }

        return $new_path;
    }

    protected function createFilename(UploadedFile $file) {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) .'-'. time() .'-'. Str::random(10) .'.' . $extension;
        return $new_filename;
    }

    protected function response($error_bag) {
        if(count($error_bag) > 0){
            json_message($error_bag[0], 'error');
        }
        
        $response =  parent::$success_response;
        return response()->json($response);
    }

    protected function fileIsValid($file_path)
    {
        $file = new UploadedFile($file_path, basename($file_path));

        if (empty($file)) {
            array_push($this->errors, parent::error('file-empty'));
            return false;
        }

        if (! $file instanceof UploadedFile) {
            array_push($this->errors, parent::error('instance'));
            return false;
        }

        if ($file->getError() != UPLOAD_ERR_OK) {
            $msg = 'File failed to upload. Error code: ' . $file->getError();
            array_push($this->errors, $msg);
            return false;
        }

        $mimetype = $file->getMimeType();

        // Bytes to MB
        $type_key = $this->currentLfmType();
        $max_size = config('lfm.storage.'. $type_key .'.max_file_size');
        $file_size = $file->getSize();

        $valid_mimetypes = config('lfm.storage.'. $type_key .'.mimetypes', []);
        if (false === in_array($mimetype, $valid_mimetypes)) {
            array_push($this->errors, parent::error('mime') . $mimetype);
            return false;
        }

        if ($max_size > 0) {
            if ($file_size > ($max_size * 1024 * 1024)) {
                array_push($this->errors, parent::error('size', ['max' => $max_size .' MB']));
                return false;
            }
        }

        return true;
    }

    protected function replaceInsecureSuffix($name)
    {
        return preg_replace("/\.php$/i", '', $name);
    }

    protected function makeThumb($new_filename)
    {
        // create thumb folder
        parent::createFolderByPath(parent::getThumbPath());

        // create thumb image
        Image::make(parent::getCurrentPath($new_filename))
            ->fit(config('lfm.thumb_img_width', 200), config('lfm.thumb_img_height', 200))
            ->save(parent::getThumbPath($new_filename));
    }

    protected function useFile($new_filename)
    {
        $file = parent::getFileUrl($new_filename);

        $responseType = request()->input('responseType');
        if ($responseType && $responseType == 'json') {
            return [
                "uploaded" => 1,
                "fileName" => $new_filename,
                "url" => $file,
            ];
        }

        return "<script type='text/javascript'>

        function getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
            var match = window.location.search.match(reParam);
            return ( match && match.length > 1 ) ? match[1] : null;
        }

        var funcNum = getUrlParam('CKEditorFuncNum');

        var par = window.parent,
            op = window.opener,
            o = (par && par.CKEDITOR) ? par : ((op && op.CKEDITOR) ? op : false);

        if (op) window.close();
        if (o !== false) o.CKEDITOR.tools.callFunction(funcNum, '$file');
        </script>";
    }

    protected function pathinfo($path, $options = null)
    {
        $path = urlencode($path);
        $parts = is_null($options) ? pathinfo($path) : pathinfo($path, $options);
        if (is_array($parts)) {
            foreach ($parts as $field => $value) {
                $parts[$field] = urldecode($value);
            }
        } else {
            $parts = urldecode($parts);
        }

        return $parts;
    }
}
