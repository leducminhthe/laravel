<?php
namespace App\Http\Controllers\Backend;

use App\Exports\LevelSubjectExport;
use App\Imports\ImportSubject;
use App\Jobs\NotifyUserOfCompletedImportSubject;
use App\Models\Categories\LevelSubject;
use App\Models\Notifications;
use App\Models\Categories\TrainingProgram;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Modules\Capabilities\Entities\CapabilitiesTitleSubject;
use App\Imports\LevelSubjectImport;
use App\Models\Categories\Subject;

class LevelSubjectController extends Controller
{
    public function index() {
        \Session::forget('errors');
        return view('backend.category.level_subject.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        LevelSubject::addGlobalScope(new DraftScope());
        $query = LevelSubject::query();
        $query->select(['el_level_subject.*', 'b.name AS parent_name']);
        $query->from('el_level_subject');
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_level_subject.training_program_id');

        if ($search) {
            $query->orWhere('el_level_subject.code', 'like', '%'. $search .'%');
            $query->orWhere('el_level_subject.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('el_level_subject.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('backend.category.level_subject.edit', ['id' => $row->id]);
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = LevelSubject::where('id', $request->id)->first();
        $training_program = TrainingProgram::find($model->training_program_id);
        json_result([
            'model' => $model,
            'training_program' => $training_program,            
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'training_program_id' => 'required',
            'code' => 'required|unique:el_level_subject,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ], $request, LevelSubject::getAttributeName());

        $model = LevelSubject::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->updated_by = profile()->user_id;
        $model->training_program_id = $request->training_program_id;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $related = Subject::whereIn('level_subject_id', $ids)->first();
        if ($related){
            json_message('Không thể xoá. Có dữ liệu liên quan chuyên đề: '. $related->name, 'error');
        }
        LevelSubject::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
    public function export()
    {
        return (new LevelSubjectExport())->download('danh_sach_cap_do_'. date('d_m_Y') .'.xlsx');
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new LevelSubjectImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('backend.category.level_subject')
        ]);
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
                $model = LevelSubject::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = LevelSubject::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }
}
