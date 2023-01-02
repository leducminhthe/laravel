<?php

namespace Modules\RegisterTrainingPlan\Http\Controllers;

use App\Models\Categories\Area;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Subject;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ProfileView;
use Illuminate\Support\Facades\Auth;
use Modules\RegisterTrainingPlan\Entities\RegisterTrainingPlan;
use Modules\RegisterTrainingPlan\Exports\ExportTemplateRegister;
use Modules\RegisterTrainingPlan\Imports\ImportCourse;

class RegisterTrainingPlanController extends Controller
{
    public function index()
    {
        return view('registertrainingplan::backend.index');
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        $subject_id = $request->input('subject_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = RegisterTrainingPlan::query();
        $query->select([
            'el_register_training_plan.*',
        ]);
        $query->from('el_register_training_plan');
        $query->where('el_register_training_plan.created_by', '=', profile()->user_id);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_register_training_plan.name', 'like', '%' . $search . '%');
            });
        }

        if ($training_program_id) {
            $query->where('el_register_training_plan.training_program_id', '=', $training_program_id);
        }
        if ($level_subject_id){
            $query->where('el_register_training_plan.level_subject_id', '=', $level_subject_id);
        }
        if ($subject_id) {
            $query->where('el_register_training_plan.subject_id', '=', $subject_id);
        }

        if ($start_date) {
            $query->where('el_register_training_plan.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('el_register_training_plan.start_date', '<=', date_convert($end_date, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy('el_register_training_plan.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.register_training_plan.edit', ['id' => $row->id]);

            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at);

            $course_time = preg_replace("/[^0-9]/", '', $row->course_time);
            $course_time_unit = preg_replace("/[^a-z]/", '', $row->course_time);
            switch($course_time_unit){
                case 'day':
                    $text_course_time_unit = 'Ngày'; break;
                case 'hour':
                    $text_course_time_unit = 'Giờ'; break;
                case 'session':
                    $text_course_time_unit = 'Buổi'; break;
                default:
                    $text_course_time_unit = 'Giờ'; break;
            }
            $row->course_time = $course_time ? ($course_time.' '.$text_course_time_unit) : '';

            $row->send = $row->send == 1 ? 'Đã gửi PĐT' : 'Chưa gửi PĐT';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($id = null)
    {
        $areas = Area::where('status', '=', 1)->get();
        $training_forms = TrainingForm::get();

        if ($id) {
            $model = RegisterTrainingPlan::find($id);
            if (!$model) abort(404);

            $page_title = $model->name;
            $subject = Subject::where('id', $model->subject_id)->first();
            $training_program = TrainingProgram::where('id', $model->training_program_id)->first();
            $level_subject = LevelSubject::find($model->level_subject_id);

            $course_time = preg_replace("/[^0-9]/", '', $model->course_time);
            $course_time_unit = preg_replace("/[^a-z]/", '', $model->course_time);

            $training_area = !empty($model->training_area_id) ? json_decode($model->training_area_id) : [];
            $teacher_id = !empty($model->teacher_id) ? json_decode($model->teacher_id) : [];
            $teachers = ProfileView::whereIn('user_id', $teacher_id)->get(['user_id', 'full_name']); //Mọi nhân viên đều có thể chọn thành GV

            return view('registertrainingplan::backend.form', [
                'model' => $model,
                'page_title' => $page_title,
                'training_program' => $training_program,
                'subject' => $subject,
                'level_subject' => $level_subject,
                'course_time' => $course_time,
                'course_time_unit' => $course_time_unit,
                'training_forms' => $training_forms,
                'training_area' => $training_area,
                'areas' => $areas,
                'teachers' => $teachers,
            ]);
        }

        $model = new RegisterTrainingPlan();
        $page_title = trans('labutton.add_new') ;

        return view('registertrainingplan::backend.form', [
            'model' => $model,
            'page_title' => $page_title,
            'course_time' => null,
            'course_time_unit' => null,
            'training_forms' => $training_forms,
            'areas' => $areas,
        ]);
    }

    public function save(Request $request)
    {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'name' => 'required',
            'course_time' => 'nullable',
            'start_date' => 'required|date_format:d/m/Y',
            'training_form_id' => 'required',
            'course_belong_to' => 'required',
        ], $request);

        $course_time_unit = $request->post('course_time_unit');
        $subject = Subject::find($request->subject_id);
        $course_type = $request->course_type;

        if($course_type == 2 && empty($request->input('end_date'))){
            json_message('Ngày kết thúc không được trống', 'error');
        }

        $model = RegisterTrainingPlan::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        $model->start_date = date_convert($request->input('start_date'));
        $model->end_date = $request->input('end_date') ? date_convert($request->input('end_date'), '23:59:59') : null;
        $model->course_time = $request->input('course_time') . ' ' . $course_time_unit;
        $model->level_subject_id = $subject->level_subject_id;
        $model->training_area_id = is_array($request->training_area_id) ? json_encode($request->training_area_id) : '';
        $model->teacher_id = is_array($request->teacher_id) ? json_encode($request->teacher_id) : '';

        if ($model->end_date) {
            if ($model->start_date > $model->end_date) {
                json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
            }
        }
        if (empty($model->id)) {
            $model->created_by = profile()->user_id;
        }
        $model->updated_by = profile()->user_id;
        $model->course_type = $course_type;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.register_training_plan.edit', ['id' => $model->id])
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);

        foreach($ids as $id){
            $model = RegisterTrainingPlan::find($id);
            if($model->send == 1){
                json_result([
                    'status' => 'error',
                    'message' => 'Đã gửi PĐT. Không thể xoá',
                ]);
            }else{
                $model->delete();
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function import(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ImportCourse();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.register_training_plan.management');

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình import',
            'redirect' => $redirect,
        ]);
    }

    public function exportTemplate(){
        return (new ExportTemplateRegister())->download('mau_import_dang_ky_ke_hoach_dao_tao_thang_'. date('d_m_Y') .'.xlsx');
    }

    public function send(Request $request){
        $ids = $request->input('ids', null);

        RegisterTrainingPlan::whereIn('id', $ids)->update([
            'send' => 1,
        ]);

        json_message('Đã gửi Phòng đào tạo thành công','success');
    }
}
