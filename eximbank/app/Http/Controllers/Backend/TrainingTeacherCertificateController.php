<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingTeacherCertificate;

class TrainingTeacherCertificateController extends Controller
{
    public function index($teacher_id) {
        return view('backend.category.training_teacher.certificate');
    }

    public function getData($teacher_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = TrainingTeacherCertificate::query();
        $query->where('training_teacher_id', $teacher_id);

        if ($search) {
            $query->where('name_certificate', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->time_start = get_date($row->time_start, 'd/m/Y');
            $row->date_license = get_date($row->date_license, 'd/m/Y');
            $row->date_effective = get_date($row->date_effective, 'd/m/Y');

            $row->certificate = basename($row->certificate);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($teacher_id, Request $request) {
        $model = TrainingTeacherCertificate::findOrFail($request->id);
        $path_image = $model->certificate ? image_file($model->certificate) : null;

        $model->time_start = get_date($model->time_start);
        $model->date_license = get_date($model->date_license);
        $model->date_effective = get_date($model->date_effective);

        json_result([
            'model' => $model,
            'path_image' => $path_image,
        ]);
    }

    public function save($teacher_id, Request $request) {
        $this->validateRequest([
            'name_certificate' => 'required',
            'name_school' => 'required',
            'result' => 'required',
            'certificate' => 'required'
        ],$request, TrainingTeacherCertificate::getAttributeName());

        $model = TrainingTeacherCertificate::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());
        $model->training_teacher_id = $teacher_id;
        $model->time_start = $request->time_start ? get_date($request->time_start, 'Y-m-d') : null;
        $model->date_license = $request->date_license ? get_date($request->date_license, 'Y-m-d') : null;
        $model->date_effective = $request->date_effective ? get_date($request->date_effective, 'Y-m-d') : null;
        $model->certificate = path_upload($request->certificate);

        if($model->save()){
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove($teacher_id, Request $request) {
        $ids = $request->input('ids', null);

        TrainingTeacherCertificate::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    // LẤY HÌNH ẢNH CHỨNG CHỈ
    public function showImage(Request $request)
    {
        $training_teacher_certificate = TrainingTeacherCertificate::find($request->id);
        $img = image_file($training_teacher_certificate->certificate);

        json_result([
            'status' => 'success',
            'img' => $img,
        ]);
    }
}
