<?php

namespace App\Http\Controllers\FileManager;

use App\Models\Warehouse;
use App\Models\WarehouseFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Libraries\Entities\Libraries;
use Modules\News\Entities\News;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseActivityFile;
use Modules\Online\Entities\OnlineCourseActivityXapi;
use Modules\Online\Entities\OnlineCourseDocument;
use Modules\Offline\Entities\OfflineCourseActivityFile;
use Modules\Offline\Entities\OfflineCourseActivityScorm;
use Modules\Offline\Entities\OfflineCourseActivityXapi;
use Modules\Offline\Entities\OfflineCourseDocument;
use Modules\CoursePlan\Entities\CoursePlan;
use Modules\Certificate\Entities\Certificate;
use App\Models\KpiTemplate;
use Modules\Quiz\Entities\QuizTemplates;
use Modules\Quiz\Entities\Quiz;
use Modules\Promotion\Entities\Promotion;
use Modules\Promotion\Entities\PromotionGroup;
use Modules\Promotion\Entities\PromotionLevel;
use App\Models\LoginImage;
use App\Models\LogoModel;
use Modules\UserMedal\Entities\UserMedal;
use Modules\TopicSituations\Entities\Topic;

class FolderController extends LfmController
{
    public function getFolders()
    {
        $root_folders = [];
        $type = $this->currentLfmType();
        $path = $this->getPath();
        $parentFolder = WarehouseFolder::find($path);

        $folders = WarehouseFolder::getDirectories($path, $type);
        $root_folders[] = (object) [
            'name' => $parentFolder->name,
            'path' => $path,
            'children' => $folders,
            'has_next' => false,
        ];

        return view('file-manager.tree')
            ->with(compact('root_folders'));
    }

    public function getAddfolder(Request $request)
    {
        $folder_name = $request->post('name');
        $type = $this->currentLfmType();
        $current_folder = trim($request->post('parent'));

        if ($current_folder <= 0) {
            $current_folder = null;
        }

        if (empty($folder_name)) {
            return $this->error('folder-name');
        }

        $folder = new WarehouseFolder();
        $folder->name = $folder_name;
//        $folder->user_id = profile()->user_id;
        $folder->type = $type;
        $folder->parent_id = $current_folder;
        $folder->save();

        return parent::$success_response;
    }

    public function delete(Request $request){
        $id = $request->id;
        $is_file = $request->is_file;
        $acceptDelete = $request->acceptDelete;
        $type = $request->type;
        if ($is_file){
            $folder = Warehouse::find($id);
            if($folder->type == 'image' || $folder->type == 'images') {
                $check = $this->checkModel($folder->file_path);
                if($check && $acceptDelete == 0) {
                    return parent::$error_response;
                }
            } else {
                $check = $this->checkModelFile($folder->file_path);
                if($check) {
                    return 'File đã tồn tại không thể xóa';
                }
            }
            $storage = \Storage::disk('upload');
            if ($storage->exists($folder->file_path)) {
                \Storage::disk('upload')->delete($folder->file_path);
            }
            $folder->delete();
            return 'OK';
        }else{
            $storage = \Storage::disk('upload');
            $folder = WarehouseFolder::find($id);
            $folderItems = Warehouse::where('folder_id', $folder->id)->get();
            foreach ($folderItems as $key => $item) {
                if ($storage->exists($item->file_path)) {
                    \Storage::disk('upload')->delete($item->file_path);
                }
                $item->delete();
            }
            $folder->delete();
            return 'OK';
        }
    }

    public function checkModel($filePath) {
        switch (session()->get('url_filemanager')) {
            case 'topic-situations':
                $check = Topic::where('image', $filePath)->count();
                break;
            case 'usermedal':
                $check = UserMedal::where('photo', $filePath)->count();
                break;
            case 'logo':
                $check = LogoModel::where('image', $filePath)->count();
                break;
            case 'login-image':
                $check = LoginImage::where('image', $filePath)->count();
                break;
            case 'promotion-level':
                $check = PromotionLevel::where('images', $filePath)->count();
                break;
            case 'promotion-group':
                $check = PromotionGroup::where('icon', $filePath)->count();
                break;
            case 'promotion':
                $check = Promotion::where('images', $filePath)->count();
                break;
            case 'quiz':
                $check = Quiz::where('img', $filePath)->count();
                break;
            case 'quiz-template':
                $check = QuizTemplates::where('img', $filePath)->count();
                break;
            case 'libraries':
                $check = Libraries::where('image', $filePath)->count();
                break;
            case 'news':
                $check = News::where('image', $filePath)->count();
                break;
            case 'online':
                $check = OnlineCourse::where('image', $filePath)->count();
                break;
            case 'offline':
                $check = OfflineCourse::where('image', $filePath)->count();
                break;
            case 'course-plan':
                $check = CoursePlan::where('image', $filePath)->count();
                break;
            case 'certificate':
                $check = Certificate::where('image', $filePath)->count();
                break;
            case 'kpi-template':
                $check = KpiTemplate::where('image', $filePath)->count();
                break;
            default:
                $check = 0;
                break;
        }

        return $check > 0 ? true : false;
    }

    public function checkModelFile($filePath) {
        switch (session()->get('url_filemanager')) {
            case 'libraries':
                $check = Libraries::where('attachment', $filePath)->count();
                break;
            case 'online':
                $check = 0;
                $check_scrom = OnlineCourseActivityScorm::where('path', $filePath)->count();
                $check_file_activity = OnlineCourseActivityFile::where('path', $filePath)->count();
                $check_xapi = OnlineCourseActivityXapi::where('path', $filePath)->count();
                $check_document = OnlineCourseDocument::where('document', $filePath)->count();
                if($check_scrom > 0 || $check_file_activity > 0 || $check_xapi > 0 || $check_document > 0) {
                    $check = 1;
                }
                break;
            case 'offline':
                $check = 0;
                $check_scrom = OfflineCourseActivityScorm::where('path', $filePath)->count();
                $check_file_activity = OfflineCourseActivityFile::where('path', $filePath)->count();
                $check_xapi = OfflineCourseActivityXapi::where('path', $filePath)->count();
                $check_document = OfflineCourseDocument::where('document', $filePath)->count();
                if($check_scrom > 0 || $check_file_activity > 0 || $check_xapi > 0 || $check_document > 0) {
                    $check = 1;
                }
                break;
            default:
                $check = 0;
                break;
        }

        return $check > 0 ? true : false;
    }
}
