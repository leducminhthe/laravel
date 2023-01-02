<?php

namespace Modules\TargetManager\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\TargetManager\Entities\TargetManager;
use Modules\TargetManager\Entities\TargetManagerParent;
use Modules\TargetManager\Entities\TargetManagerGroup;
use Modules\TargetManager\Imports\ImportTargetManager;

class TargetManagerController extends Controller
{
    public function index($parent_id)
    {
        $errors = session()->get('errors');
        \Session::forget('errors');

        $target_manager_parent = TargetManagerParent::find($parent_id);
        $target_manager_parent_other = TargetManagerParent::where('id', '!=', $parent_id)->get();

        return view('targetmanager::backend.target_manager.index',[
            'target_manager_parent' => $target_manager_parent,
            'target_manager_parent_other' => $target_manager_parent_other,
        ]);
    }

    public function getData($parent_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = TargetManager::query();
        $query->where('parent_id', $parent_id);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            switch ($row->type) {
                case 1:
                    $row->type = trans('latraining.title');
                    break;
                case 2:
                    $row->type = trans('latraining.student');
                    break;
                default:
                    $row->type = '';
                    break;
            }
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save($parent_id, Request $request) {
        $this->validateRequest([
            'name' => 'required',
        ], $request, TargetManager::getAttributeName());

        $model = TargetManager::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->parent_id = $parent_id;
        if ($model->save()) {
            TargetManagerGroup::where('target_manager_id', $model->id)->delete();
            if(isset($request->title)) {
                foreach($request->title as $title) {
                    $save = new TargetManagerGroup();
                    $save->target_manager_id = $model->id;
                    $save->title_id = $title;
                    $save->save();
                }
            } else if (isset($request->user)) {
                foreach($request->user as $user) {
                    $save = new TargetManagerGroup();
                    $save->target_manager_id = $model->id;
                    $save->user_id = $user;
                    $save->save();
                }
            }

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function form($parent_id, Request $request) {
        $model = TargetManager::where('id', $request->id)->first();

        $titles = '';
        $profile = '';
        if($model->type == 1){
            $group = TargetManagerGroup::where('target_manager_id', $request->id)->pluck('title_id')->toArray();
            $titles = Titles::whereIn('id', $group)->get(['id', 'code', 'name'])->toArray();
        }
        if($model->type == 2){
            $group = TargetManagerGroup::where('target_manager_id', $request->id)->pluck('user_id')->toArray();
            $profile = ProfileView::whereIn('user_id', $group)->get(['user_id', 'code', 'full_name'])->toArray();
        }

        json_result([
            'model' => $model,
            'titles' => $titles,
            'profile' => $profile,
        ]);
    }

    public function remove($parent_id, Request $request) {
        $ids = $request->input('ids', null);

        TargetManager::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function copy(Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('lamenu.target_manager'),
        ]);

        $parent_id = $request->parent_new_id;
        $ids = $request->input('ids', null);
        foreach($ids as $id){
            $model = TargetManager::find($id);
            $newModel = $model->replicate();
            $newModel->parent_id = $parent_id;
            $newModel->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.copy_success'),
            'redirect' => route('module.target_manager', ['parent_id' => $parent_id])
        ]);
    }

    public function import($parent_id, Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $type_import = $request->type_import;
        $import = new ImportTargetManager($parent_id, $type_import);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.target_manager', [$parent_id])
        ]);
    }
}
