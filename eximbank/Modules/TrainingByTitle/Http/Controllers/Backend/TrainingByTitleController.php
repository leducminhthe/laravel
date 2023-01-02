<?php

namespace Modules\TrainingByTitle\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Categories\Titles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\TrainingByTitle\Entities\TrainingByTitle;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;
use Modules\TrainingByTitle\Entities\TrainingByTitleDetail;
use Modules\TrainingByTitle\Entities\TrainingByTitleUploadImage;
use App\Models\Categories\UnitType;
use Modules\TrainingByTitle\Exports\ExportTrainingByTitle;
use Modules\TrainingByTitle\Imports\ImportTrainingByTitle;
use App\Models\Categories\TitleRank;

class TrainingByTitleController extends Controller
{
    public function index(){
        $errors = session()->get('errors');
        \Session::forget('errors');
        $unit_types = UnitType::get();
        // return view('trainingbytitle::backend.training_by_title.index', [
        return view('backend.learning_manager.index',[
            'errors' => $errors,
            'unit_types' => $unit_types
        ]);
    }

    public function getData(Request $request) {
        $title = $request->input('title');
        $unit_type = $request->input('unit_type');
        $title_rank = $request->input('title_rank');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = Titles::query();
        $query->select(['a.*','b.name as unit_type_name']);
        $query->from('el_titles as a');
        $query->leftJoin('el_unit_type as b','b.id','=','a.unit_type');
        $query->where('a.status',1);
        
        if ($title){
            $query->where('a.id', '=', $title);
        }
        if ($unit_type){
            $query->where('b.id', '=', $unit_type);
        }

        if($title_rank) {
            $query->where('a.group',$title_rank);
        }

        $count = $query->count();
        $query->orderBy('a.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->num_training_by_title_category = TrainingByTitleCategory::query()->where('title_id', '=', $row->id)->count();
            $row->title_url = route('module.training_by_title.detail', ['id' => $row->id]);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function save(Request $request){
        $this->validateRequest([
            'title_id' => 'required|exists:el_titles,id',
        ], $request, [
            'title_id' => trans('app.title'),
        ]);
        $title_id = $request->post('title_id');
        $title = Titles::find($title_id);

        $model = TrainingByTitle::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->title_id = $title_id;
        $model->title_name = $title->name;

        if ($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image);
        }
        
        $model->save();

        return response()->json([
            'status' => 'success',
            'message' => trans('backend.save_success'),
            'redirect' => route('module.training_by_title'),
        ]);
    }

    public function remove(Request $request){
        $id = $request->id;

        TrainingByTitleDetail::query()->where('training_title_id', '=', $id)->delete();
        TrainingByTitleCategory::query()->where('training_title_id', '=', $id)->delete();
        TrainingByTitle::find($id)->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function checkTrainingByTitle(Request $request){
        $title_old = $request->title_old;
        $title_new = $request->title_new;

        if ($title_old == $title_new){
            return json_result([
                'status' => 'error',
                'message' => 'Chức danh nguồn phải khác chức danh đích',
            ]);
        }

        $trainingByTitle = TrainingByTitleCategory::query()->where('title_id', '=', $title_new);
        if ($trainingByTitle->exists()){
            return json_result([
                'status' => 'warning',
                'message' => 'Chức danh đã có Lộ trình. Bạn vẫn muốn sao chép?',
            ]);
        }

        return json_result([
            'status' => 'success',
            'message' => 'Bắt đầu sao chép?',
        ]);
    }

    public function copy(Request $request){
        $title_old = $request->title_old;
        $title_news = $request->title_new;
        
        $trainingByTitles = TrainingByTitleCategory::query()->where('title_id', '=', $title_old)->get();
        foreach ($trainingByTitles as $item){
            foreach ($title_news as $key => $title_new) {
                $newTrainingByTitle = TrainingByTitleCategory::firstOrNew(['title_id'=>$title_new, 'name'=>$item->name]);
                $newTrainingByTitle->title_id = $title_new;
                $newTrainingByTitle->training_title_id = $title_new;
                $newTrainingByTitle->num_date_category = $item->num_date_category;
                $newTrainingByTitle->name = $item->name;
                $newTrainingByTitle->save();
                
                $trainingTitlesDetail = TrainingByTitleDetail::where('training_title_category_id',$item->id)->get();

                foreach($trainingTitlesDetail as $trainingTitleDetail) {
                    $newTrainingByTitleDetail = TrainingByTitleDetail::firstOrNew(['title_id'=>$title_new, 'training_title_category_id'=>$newTrainingByTitle->id, 'subject_id' => $trainingTitleDetail->subject_id]);
                    $newTrainingByTitleDetail->training_title_id = $title_new;
                    $newTrainingByTitleDetail->title_id = $title_new;
                    $newTrainingByTitleDetail->training_title_category_id = $newTrainingByTitle->id;
                    $newTrainingByTitleDetail->subject_id = $trainingTitleDetail->subject_id;
                    $newTrainingByTitleDetail->subject_code = $trainingTitleDetail->subject_code;
                    $newTrainingByTitleDetail->subject_name = $trainingTitleDetail->subject_name;
                    $newTrainingByTitleDetail->num_date = $trainingTitleDetail->num_date;
                    $newTrainingByTitleDetail->num_time = $trainingTitleDetail->num_time;
                    $newTrainingByTitleDetail->save();
                }
            }
        }

        return json_result([
            'status' => 'success',
            'message' => trans('laother.copy_success'),
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ImportTrainingByTitle();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        json_result([
            'status' => 'success',
            'message' => 'Import ' . trans('laapi.success'),
            'redirect' => route('module.training_by_title')
        ]);
    }

    public function export(Request $request){
        return (new ExportTrainingByTitle())->download('danh_sach_lo_trinh_dao_tao_'. date('d_m_Y') .'.xlsx');
    }

    public function uploadImage(){
        return view('trainingbytitle::backend.upload_image.index');
    }

    public function editUploadImage(Request $request) {
        $model = TrainingByTitleUploadImage::find($request->id);
        $path_image = image_file($model->image);
        json_result([
            'model' => $model,
            'image' => $path_image
        ]);
    }

    public function getDataUploadImage(Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $query = TrainingByTitleUploadImage::query();

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        foreach ($rows as $row) {
            $row->image2 = image_file($row->image);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveUploadImage(Request $request) {
        $this->validateRequest([
            'image' => 'required|',
        ], $request, [
            'image' => 'Ảnh',
        ]);

        $model = TrainingByTitleUploadImage::firstOrNew(['id' => $request->id]);
        $sizes = config('image.sizes.advertising_photo');
        $model->image = upload_image($sizes, $request->image);

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function ajaxTrainingByTitleCategory(Request $request) {
        $titleId = $request->titleId;
        $category = TrainingByTitleCategory::where('title_id', $titleId)->get();

        json_result($category);
    }
}
