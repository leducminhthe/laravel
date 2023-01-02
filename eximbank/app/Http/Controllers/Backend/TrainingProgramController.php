<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Subject;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingProgram;
use App\Exports\TrainingProgramExport;
use App\Imports\TrainingProgramImport;

class TrainingProgramController extends Controller
{
    public function index() {
        \Session::forget('errors');
        return view('backend.category.training_program.index');
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'order');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        TrainingProgram::addGlobalScope(new DraftScope());
        $query = TrainingProgram::query();
        if ($search) {
            $query->orWhere('code', 'like', '%'. $search .'%');
            $query->orWhere('name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('order', $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $created_by = $row->created_by ? $row->created_by : 2;
            $updated_by = $row->updated_by ? $row->updated_by : 2;
            $row->info = route('backend.get_user_info',['created' => $created_by, 'updated' => $updated_by]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form(Request $request) {
        $model = TrainingProgram::select(['id','status','code','name'])->where('id', $request->id)->first();
        json_result($model);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'code' => 'required|unique:el_training_program,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1'
        ], $request, TrainingProgram::getAttributeName());
        
        $model = TrainingProgram::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        if ($request->id) {
            $model->created_by = $model->created_by;
        }
        $model->order = TrainingProgram::count() + 1;
        $model->updated_by = profile()->user_id;
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
        $related = Subject::whereIn('training_program_id', $ids)->first();
        if ($related){
            json_message(trans('laother.related_data'). '. ' .trans('laother.can_not_delete'), 'error');
        }
        TrainingProgram::destroy($ids);

        // Lưu lại sắp xếp
        $allTrainingProgram = TrainingProgram::orderBy('id', 'ASC')->get();
        $order = 0;
        foreach ($allTrainingProgram as $key => $trainingProgram) {
            $order += 1;
            $saveOrder = TrainingProgram::find($trainingProgram->id);
            $saveOrder->order = $order;
            $saveOrder->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function export(){
        return (new TrainingProgramExport())->download('danh_sach_chuong_trinh_dao_tao_'. date('d_m_Y') .'.xlsx');
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new TrainingProgramImport();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('backend.category.training_program')
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
                $model = TrainingProgram::findOrFail($id);
                $model->status = $status;
                $model->save();
            }
        } else {
            $model = TrainingProgram::findOrFail($ids);
            $model->status = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function saveOrder(Request $request) 
    {
        $order = $request->order;
        if(count(array_unique($order)) < count($order)){
            json_message('vị trí sắp xếp chưa đúng', 'error');
        } 
        foreach ($order as $id => $value) {
            TrainingProgram::where(['id'=>$id])->update(['order' => $value]);
        }
        return json_result([
            'status' => 'success',
            'message' => trans('laother.update_successful'),
        ]);
    }
}
