<?php

namespace Modules\CoursePlan\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Categories\Area;
use App\Models\Categories\District;
use App\Models\Categories\LevelSubject;
use App\Models\Categories\Province;
use App\Models\Categories\Subject;
use App\Models\Categories\TeacherType;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingCost;
use App\Models\Categories\TrainingForm;
use App\Models\Categories\TrainingLocation;
use App\Models\Categories\TrainingObject;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\TrainingProgram;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TrainingType;
use App\Models\Categories\Unit;
use App\Models\Categories\UnitManager;
use App\Models\Profile;
use App\Scopes\DraftScope;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Certificate\Entities\Certificate;
use Modules\CoursePlan\Entities\CoursePlan;
use Modules\CoursePlan\Entities\CoursePlanCondition;
use Modules\CoursePlan\Entities\CoursePlanCost;
use Modules\CoursePlan\Entities\CoursePlanObject;
use Modules\CoursePlan\Entities\CoursePlanschedule;
use Modules\CoursePlan\Entities\CoursePlanTeacher;
use Modules\Offline\Entities\OfflineCondition;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseCost;
use Modules\Offline\Entities\OfflineObject;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseCost;
use Modules\Online\Entities\OnlineCourseUpload;
use Modules\Online\Entities\OnlineObject;
use Modules\PermissionApproved\Entities\ApprovedModelTracking;
use Modules\PlanApp\Entities\PlanAppTemplate;
use Modules\Quiz\Entities\Quiz;
use Modules\Rating\Entities\RatingTemplate;
use Modules\TrainingPlan\Entities\TrainingPlan;
use Modules\VirtualClassroom\Entities\VirtualClassroom;
use Nwidart\Modules\Collection;
use Nwidart\Modules\Facades\Module;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use App\Models\Categories\UnitType;
use App\Models\ProfileView;
use Modules\CoursePlan\Imports\ImportOnlineCourse;
use Modules\CoursePlan\Imports\ImportOfflineCourse;
use Modules\Notify\Entities\Notify;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\RegisterTrainingPlan\Entities\RegisterTrainingPlan;
use Modules\CoursePlan\Exports\RegisterTrainingPlanOnline;
use Modules\CoursePlan\Exports\RegisterTrainingPlanOffline;

class CoursePlanController extends Controller
{
    public function index()
    {
        \Session::forget('errors');
        // return view('courseplan::backend.index');
        return view('backend.training.index',[
        ]);
    }

    public function getData(Request $request)
    {
        $search = $request->input('search');
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        $subject_id = $request->input('subject_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $unit = $request->unit_id;
        $course_belong_to = $request->course_belong_to;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $prefix= \DB::getTablePrefix();

        //CoursePlan::addGlobalScope(new DraftScope());
        $query = CoursePlan::query();
        $query->select([
            'el_course_plan.*',
            'c.name AS subject_name',
            'e.full_name',
            'e.unit_name',
        ])->disableCache();
        $query->from('el_course_plan');
        $query->leftJoin('el_training_program AS b', 'b.id', '=', 'el_course_plan.training_program_id');
        $query->leftJoin('el_subject AS c', 'c.id', '=', 'el_course_plan.subject_id');
        $query->leftJoin('el_level_subject as d', 'd.id', '=', 'el_course_plan.level_subject_id');
        $query->leftJoin('el_profile_view as e', 'e.user_id', '=', 'el_course_plan.created_by');

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('el_course_plan.name', 'like', '%' . $search . '%');
                $subquery->orWhere('el_course_plan.code', 'like', '%' . $search . '%');
            });
        }

        if ($training_program_id) {
            $query->where('b.id', '=', $training_program_id);
        }
        if ($level_subject_id){
            $query->where('d.id', '=', $level_subject_id);
        }
        if ($subject_id) {
            $query->where('c.id', '=', $subject_id);
        }

        if ($start_date) {
            $query->where('el_course_plan.start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('el_course_plan.start_date', '<=', date_convert($end_date, '23:59:59'));
        }

        if ($unit) {
            $unit = Unit::whereIn('id', explode(';', $unit))->latest('id')->first();
            $unit_id = Unit::getArrayChild($unit->code);

            $query->where(function ($sub_query) use ($unit_id, $unit) {
                $sub_query->orWhereIn('e.unit_id', $unit_id);
                $sub_query->orWhere('e.unit_id', '=', $unit->id);
            });
        }

        if($course_belong_to){
            $query->where('el_course_plan.course_belong_to', '=', $course_belong_to);
        }

        $count = $query->count();
        $query->orderBy('el_course_plan.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->edit_url = route('module.course_plan.edit', ['course_type' => $row->course_type, 'id' => $row->id]);
            $row->register_deadline = get_date($row->register_deadline);
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->document = $row->document ? link_download('uploads/'.$row->document) : '';

            $row->approved_by = $row->approved_by ? Profile::fullname($row->approved_by) : '';
            $row->time_approved = $row->time_approved ? get_date($row->time_approved, 'h:i d/m/Y') : '';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function form($course_type, $id = null)
    {
        $titles = Titles::select(['id','name','code'])->where('status', '=', 1)->get();
        $training_objects = TrainingObject::get();
        $areas = Area::where('status', '=', 1)->get();
        $training_partners = TrainingPartner::get();
        $training_types = TrainingType::where('status', '=', 1)->get();
        $units_type = UnitType::get();

        $training_costs = TrainingCost::get();
        $course_cost = CoursePlanCost::where('course_id', '=', $id)->where('course_type', '=', $course_type)->get();
        $total_actual_amount = CoursePlanCost::getTotalActualAmount($id, $course_type);
        $total_plan_amount = CoursePlanCost::getTotalPlanAmount($id, $course_type);
        $teachers = CoursePlanschedule::getTeacher($course_type, $id);
        $teacher_types = TeacherType::get();
        $templates = RatingTemplate::get();
        $plan_app_template = PlanAppTemplate::get();
        $training_plan = TrainingPlan::get();
        $certificate = Certificate::all();
        $qrcode_survey_after_course = null;
        $training_forms_online = TrainingForm::where('training_type_id',1)->get();
        $training_forms_offline = TrainingForm::where('training_type_id',2)->get();
        $province = Province::all();
        $quizs = Quiz::where('status','=',1)->get();

        $unit_manager_lv2 = UnitManager::query()
            ->from('el_unit_manager AS a')
            ->join('el_profile AS b', 'b.code', '=', 'a.user_code')
            ->join('el_unit AS c', 'c.code', '=', 'a.unit_code')
            ->where('c.level', '=', 2)
            ->where('b.user_id', '=', profile()->user_id)->pluck('c.id')->toArray();

        $units = Unit::select(['id','name','code'])->where('status', '=', 1)->get();

        $corporations = Unit::select(['id','name','code'])->where('level', '=', 1)->where('status', '=', 1)->get();
        if ($id) {
            $model = CoursePlan::find($id);
            if (!$model) abort(404);
            $page_title = $model->name;
            $subject = Subject::where('id', $model->subject_id)->first();
            $training_program = TrainingProgram::where('id', $model->training_program_id)->first();
            $level_subject = LevelSubject::find($model->level_subject_id);

            $unit = explode(',', $model->unit_id);

            $course_time = preg_replace("/[^0-9]/", '', $model->course_time);
            $course_time_unit = preg_replace("/[^a-z]/", '', $model->course_time);

            $training_location_course = TrainingLocation::find($model->training_location_id);
            if ($training_location_course){
                $district = District::query()->where('province_id','=',$training_location_course->province_id)->get();

                $training_location = TrainingLocation::where('province_id','=',$training_location_course->province_id)
                    ->where('district_id','=',$training_location_course->district_id)
                    ->where('status','=',1)
                    ->get();

                $model->training_location_province = $training_location_course->province_id;
                $model->training_location_district = $training_location_course->district_id;
            }else{
                $district=null;
                $training_location=null;
            }
            $condition = CoursePlanCondition::where('course_id', '=', $id)->where('course_type', '=', $course_type)->first();

            !empty($model->document) ? $documents = json_decode($model->document) : $documents = [];
            !empty($model->training_unit) ? $training_unit = json_decode($model->training_unit) : $training_unit = [];
            !empty($model->training_partner_id) ? $training_partner = json_decode($model->training_partner_id) : $training_partner = [];
            !empty($model->training_object_id) ? $get_training_object_model_id = json_decode($model->training_object_id) : $get_training_object_model_id = [];
            !empty($model->training_area_id) ? $training_area = json_decode($model->training_area_id) : $training_area = [];
            !empty($model->training_type_id) ? $training_type = json_decode($model->training_type_id) : $training_type = [];

            $teacher_type=TeacherType::where('id', $model->teacher_type_id)->first();

            return view('courseplan::backend.form', [
                'titles' => $titles,
                'model' => $model,
                'page_title' => $page_title,
                'subject' => $subject,
                'training_program' => $training_program,
                'training_costs' => $training_costs,
                'course_cost' => $course_cost,
                'total_actual_amount' => $total_actual_amount,
                'total_plan_amount' => $total_plan_amount,
                'teachers' => $teachers,
                'teacher_types' => $teacher_types,
                'templates' => $templates,
                'plan_app_template' => $plan_app_template,
                'training_plan' => $training_plan,
                'unit' => $unit,
                'course_time' => $course_time,
                'course_time_unit' => $course_time_unit,
                'certificate' => $certificate,
                'qrcode_survey_after_course' => $qrcode_survey_after_course,
                'units' => $units,
                'unit_manager_lv2' => $unit_manager_lv2,
                'level_subject' => $level_subject,
                'corporations' => $corporations,
                'course_type' => $course_type,
                'training_forms_online' => $training_forms_online,
                'training_forms_offline' => $training_forms_offline,
                'province' => $province,
                'district' => $district,
                'training_location' => $training_location,
                'quizs' => $quizs,
                'condition' => $condition,
                'training_area' => $training_area,
                'training_partner' => $training_partner,
                'get_training_object_model_id' => $get_training_object_model_id,
                'training_type' => $training_type,
                'training_objects' => $training_objects,
                'teacher_type' => $teacher_type,
                'areas' => $areas,
                'training_partners' =>$training_partners,
                'training_unit' => $training_unit,
                'training_types' => $training_types,
                'documents' => $documents,
                'units_type' => $units_type,
            ]);
        }

        $model = new CoursePlan();
        $page_title = trans('labutton.add_new') ;
        $training_location = TrainingLocation::all();

        return view('courseplan::backend.form', [
            'model' => $model,
            'page_title' => $page_title,
            'templates' => $templates,
            'plan_app_template' => $plan_app_template,
            'training_plan' => $training_plan,
            'course_time' => null,
            'course_time_unit' => null,
            'certificate' => $certificate,
            'qrcode_survey_after_course' => $qrcode_survey_after_course,
            'units' => $units,
            'unit_manager_lv2' => $unit_manager_lv2,
            'corporations' => $corporations,
            'course_type' => $course_type,
            'training_forms_online' => $training_forms_online,
            'training_forms_offline' => $training_forms_offline,
            'province' => $province,
            'district' => null,
            'training_location' => $training_location,
            'quizs' => $quizs,
            'titles' => $titles,
            'training_objects' => $training_objects,
            'areas' => $areas,
            'training_partners' =>$training_partners,
            'training_types' => $training_types,
            'units_type' => $units_type,
        ]);
    }

    public function save($course_type, Request $request)
    {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
            'subject_id' => 'required|exists:el_subject,id',
            'name' => 'required',
            'in_plan' => 'nullable|exists:el_training_plan,id',
            'course_time' => 'nullable',
            'image' => 'nullable|string',
            'num_lesson' => 'nullable',
            'start_date' => 'required|date_format:d/m/Y',
            'document' => "nullable|array|min:1",
            'document.*' => 'nullable|mimes:doc,pdf,docx,zip,xlsx|max:4096',
            'training_form_id' => 'required',
        ], $request);

        $document_upload = [];
        if (!empty($request->hidden_document)) {
            $document_upload = $request->hidden_document;
        }
        if ($request->hasfile('document')) {
            foreach ($request->file('document') as $file) {
                $folder_id = '';

                if (empty($folder_id)) {
                    $folder_id = null;
                }

                $type = 'file';
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $new_filename = Str::slug(basename(substr($filename, 0, 50), "." . $extension)) . '-' . time() . '-' . Str::random(10) . '.' . $extension;

                $storage = \Storage::disk('upload');
                $new_path = $storage->putFileAs(date('Y/m/d'), $file, $new_filename);

                if ($new_path) {
                    $warehouse = new Warehouse();
                    $warehouse->file_name = $file->getClientOriginalName();
                    $warehouse->file_type = $file->getMimeType();
                    $warehouse->file_path = $new_path;
                    $warehouse->file_size = $file->getSize();
                    $warehouse->extension = $file->getClientOriginalExtension();
                    $warehouse->source = 'upload';
                    $warehouse->type = $type;
                    $warehouse->created_by = profile()->user_id;
                    $warehouse->updated_by = profile()->user_id;
                    $warehouse->user_id = profile()->user_id;
                    $warehouse->folder_id = $folder_id;
                    $warehouse->save();
                    $document_upload[] = $new_path;
                }
            }
        }
        // dd($document_upload);
        $course_time_unit = $request->post('course_time_unit');
        $unit_id = $request->post('unit_id');

        $subject = Subject::find($request->subject_id);

        $model = CoursePlan::firstOrNew(['id' => $request->post('id')]);
        $model->fill($request->all());
        $model->has_cert = $request->post('has_cert', 0);
        $model->start_date = date_convert($request->input('start_date'));
        $model->end_date = $request->input('end_date') ? date_convert($request->input('end_date'), '23:59:59') : null;
        $model->register_deadline = $request->input('register_deadline') ? date_convert($request->input('register_deadline'), '23:59:59') : null;

        if ($request->image) {
            $sizes = config('image.sizes.medium');
            $model->image = upload_image($sizes, $request->image);
        }

        // $model->document = path_upload($model->document);
        $model->unit_id = is_array($unit_id) ? implode(',', $unit_id) : null;
        $model->course_time = $request->input('course_time') . ' ' . $course_time_unit;
        $model->level_subject_id = $subject->level_subject_id;
        $model->commit_date = date_convert($request->input('commit_date'), '00:00:00');
        $model->document = is_array($document_upload) ? json_encode($document_upload) : '';
        $model->training_object_id = is_array($request->training_object_id) ? json_encode($request->training_object_id) : '';
        $model->training_unit = is_array($request->training_unit) ? json_encode($request->training_unit) : '';
        $model->training_partner_id = is_array($request->training_partner_id) ? json_encode($request->training_partner_id) : '';
        $model->training_area_id = is_array($request->training_area_id) ? json_encode($request->training_area_id) : '';
        $model->training_type_id = $course_type == 1 ? 1 : 2;

        if ($model->end_date) {
            if ($model->start_date > $model->end_date) {
                json_message('Ngày kết thúc phải sau Ngày bắt đầu', 'error');
            }

            if ($model->register_deadline) {
                if ($model->register_deadline >= $model->end_date) {
                    json_message('Hạn đăng ký phải trước Ngày kết thúc', 'error');
                }
            }
        }

        if (empty($request->id)) {
            if ($model->start_date < date('Y-m-d') && $course_type == 1) {
                json_message('Ngày bắt đầu tính từ hiện tại', 'error');
            }
        }

        if (empty($model->id)) {
            $model->created_by = profile()->user_id;
        }

        $model->updated_by = profile()->user_id;
        $model->status = 2;
        $model->course_type = $course_type;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.course_plan.edit', ['course_type' => $course_type, 'id' => $model->id])
            ]);
        }
        json_message(trans('laother.save_error'), 'error');
    }

    public function remove(Request $request)
    {
        $ids = $request->input('ids', null);

        CoursePlan::destroy($ids);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function ajaxGetSubject(Request $request)
    {
        $this->validateRequest([
            'training_program_id' => 'required|exists:el_training_program,id',
        ], $request, [
            'training_program_id' => trans('latraining.training_program'),
        ]);

        $training_program_id = $request->input('training_program_id');
        $subjects = Subject::where('training_program_id', '=', $training_program_id)->get();
        json_result($subjects);
    }

    public function ajaxIsopenPublish(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
            'status' => 'required|in:0,1'
        ], $request, [
            'ids' => trans('lamenu.course'),
        ]);

        $ids = $request->input('ids', null);
        $status = $request->input('status', 0);
        foreach ($ids as $id) {
            $model = CoursePlan::findOrFail($id);
            $model->isopen = $status;
            $model->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function ajaxGetCourseCode(Request $request)
    {
        $this->validateRequest([
            'subject_id' => 'required',
        ], $request, [
            'subject_id' => 'Mã học phần',
        ]);

        $subject_id = $request->input('subject_id');
        $subject = Subject::find($subject_id);
        $courses = CoursePlan::where('subject_id', '=', $subject->id)->get();

        return response()->json([
            'id' => count($courses),
            'subject_code' => $subject->code,
            'description' => $subject->description,
            'content' => $subject->content,
        ]);
    }

    public function approve(Request $request)
    {
        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        $note = $request->input('note', null);

        foreach ($ids as $id) {
            $model = CoursePlan::find($id);
            if ($model->status_convert != 1){
                (new ApprovedModelTracking())->updateApprovedTracking(CoursePlan::getModel(),$id,$status,$note);
            }else{
                json_message('Khoá học đã chuyển đổi','warning');
            }
        }

        $user_id = CoursePlan::whereIn('id', $ids)->pluck('created_by')->toArray();

        if($status == 0) {
            $notify = new Notify();
            $notify->subject = 'Thông báo từ chối đề xuất kế hoạch đào tạo tháng';
            $notify->content = 'Kế hoạch đào tạo tháng của bạn đã bị từ chối. Lý do: '.$note;
            $notify->users = $user_id;
            $notify->addMultiNotify();

            json_message('Đã từ chối','success');
        } else {
            $notify = new Notify();
            $notify->subject = 'Thông báo phê duyệt đề xuất kế hoạch đào tạo tháng';
            $notify->content = 'Kế hoạch đào tạo tháng của bạn đã được phê duyệt';
            $notify->users = $user_id;
            $notify->addMultiNotify();

            json_message('Duyệt thành công','success');
        }
    }

    public function importOnlineCourse(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $import = new ImportOnlineCourse();
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.course_plan.management');

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình import',
            'redirect' => $redirect,
        ]);
    }

    public function importOfflineCourse(Request $request){
        $this->validateRequest([
            'import_file' => 'required|file'
        ], $request, ['import_file' => 'File import']);

        $type_import = $request->type_import;
        $import = new ImportOfflineCourse($type_import);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        }

        $redirect = route('module.course_plan.management');

        json_result([
            'status' => 'success',
            'message' => 'Hoàn tất quá trình import',
            'redirect' => $redirect,
        ]);
    }

    public function saveCost($course_type, $course_id, Request $request)
    {
        $find = [',', ';', '.'];
        $cost_ids = $request->post('id');
        $plan_amounts = str_replace($find, '', $request->post('plan_amount'));
        $actual_amounts = str_replace($find, '', $request->post('actual_amount'));
        $notes = $request->post('note');

        foreach ($cost_ids as $key => $cost_id) {

            if (CoursePlanCost::checkCostExists($course_id, $course_type, $cost_id)) {
                CoursePlanCost::where('course_id', '=', $course_id)
                    ->where('course_type', '=', $course_type)
                    ->where('cost_id', '=', $cost_id)
                    ->update([
                        'plan_amount' => (float)$plan_amounts[$key] ? $plan_amounts[$key] : 0,
                        'actual_amount' => (float)$actual_amounts[$key] ? $actual_amounts[$key] : 0,
                        'notes' => $notes[$key] ? $notes[$key] : '',
                    ]);
                continue;
            }
            if($plan_amounts[$key] > 0 || $actual_amounts[$key] > 0) {
                $model = new CoursePlanCost();
                $model->cost_id = $cost_id;
                $model->plan_amount = $plan_amounts[$key] ? $plan_amounts[$key] : 0;
                $model->actual_amount = $actual_amounts[$key] ? $actual_amounts[$key] : 0;
                $model->notes = $notes[$key] ? $notes[$key] : '';
                $model->course_id = $course_id;
                $model->course_type = $course_type;
                $model->save();
            }
        }

        json_result([
            'status' => 'success',
            'message' => 'Lưu chi phí đào tạo thành công',
        ]);
    }

    public function saveObject($course_type, $course_id, Request $request)
    {
        $this->validateRequest([
            'type' => 'required|in:1,2',
        ], $request, [
            'title' => trans('latraining.title'),
            'unit' => 'Đơn vị',
            'type' => 'Loại đối tượng',
        ]);

        $titles = $request->post('title');
        $units = $request->post('unit');
        $type = $request->post('type');

        if (!$units && $titles) {
            json_message('Chưa chọn đơn vị', 'error');
        }

        if ($units && $titles){
            if (count($units) > 1){
                json_message('Khi chọn chức danh. Chỉ được chọn 1 đơn vị', 'error');
            }else{
                $unit = Unit::find($units[0]);
                foreach ($titles as $item) {
                    if (!Titles::where('id', '=', $item)->exists()) {
                        continue;
                    }

                    if (!Unit::where('id', '=', $unit->id)->exists()) {
                        continue;
                    }

                    if (CoursePlanObject::where('course_id', '=', $course_id)->where('course_type', '=', $course_type)->where('unit_id', '=', $unit->id)->whereNull('title_id')->exists()) {
                        continue;
                    }

                    if (CoursePlanObject::where('course_id', '=', $course_id)->where('course_type', '=', $course_type)->where('unit_id', '=', $unit->id)->where('title_id', '=', $item)->exists()) {
                        continue;
                    }

                    if (CoursePlanObject::where('course_id', '=', $course_id)->where('course_type', '=', $course_type)->whereNull('unit_id')->where('title_id', '=', $item)->exists()) {
                        CoursePlanObject::where('course_id', '=', $course_id)
                            ->where('course_type', '=', $course_type)
                            ->whereNull('unit_id')
                            ->where('title_id', '=', $item)
                            ->update([
                                'unit_id' => $unit->id,
                                'unit_level' => $unit->level,
                            ]);
                    }else{
                        $model = new CoursePlanObject();
                        $model->title_id = $item;
                        $model->unit_id = $unit->id;
                        $model->unit_level = $unit->level;
                        $model->type = $type;
                        $model->course_id = $course_id;
                        $model->course_type = $course_type;
                        $model->created_by = profile()->user_id;
                        $model->updated_by = profile()->user_id;
                        $model->save();
                    }
                }
            }
        }

        if ($units && !$titles) {
            foreach ($units as $item) {

                if (CoursePlanObject::where('course_id', '=', $course_id)
                    ->where('course_type', '=', $course_type)
                    ->where('unit_id', '=', $item)
                    ->exists()) {
                    continue;
                }

                if (!Unit::where('id', '=', $item)->exists()) {
                    continue;
                }

                $unit = Unit::find($item);

                $model = new CoursePlanObject();
                $model->unit_id = $item;
                $model->unit_level = $unit->level;
                $model->type = $type;
                $model->course_id = $course_id;
                $model->course_type = $course_type;
                $model->created_by = profile()->user_id;
                $model->updated_by = profile()->user_id;
                $model->save();
            }
        }

        return \response()->json([
            'status' => 'success',
            'message' => 'Thêm đối tượng thành công',
        ]);
    }

    public function getObject($course_type, $course_id, Request $request)
    {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = CoursePlanObject::query();
        $query->select([
            'a.*',
            'b.name AS title_name',
            'c.name AS unit_name'
        ]);

        $query->from('el_course_plan_object AS a');
        $query->leftJoin('el_titles AS b', 'b.id', '=', 'a.title_id');
        $query->leftJoin('el_unit AS c', 'c.id', '=', 'a.unit_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.course_type', '=', $course_type);

        $count = $query->count();
        $query->orderBy('a.' . $sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        json_result([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function removeObject($course_type, $course_id, Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request, [
            'ids' => trans('latraining.object'),
        ]);

        $item = $request->input('ids');
        CoursePlanObject::destroy($item);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function saveCondition($course_type, $course_id, Request $request){
        $this->validateRequest([
            'ratio' => 'nullable|numeric|min:1|max:100',
            'minscore' => 'nullable|numeric|min:1',
        ], $request, CoursePlanCondition::getAttributeName());

        if(CoursePlanCondition::checkExists($course_type, $course_id)){
            CoursePlanCondition::where('course_id', '=', $course_id)
                ->where('course_type', '=', $course_type)
                ->update([
                    'ratio' => $request->input('ratio'),
                    'minscore' => $request->input('minscore'),
                    'survey' => $request->survey,
                    'certificate' => $request->certificate,
                ]);
        }else{

            $model = new CoursePlanCondition();
            $model->ratio = $request->input('ratio');
            $model->minscore = $request->input('minscore');
            $model->survey = $request->survey;
            $model->certificate = $request->certificate;
            $model->course_id = $course_id;
            $model->course_type = $course_type;
            $model->save();
        }
        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    public function saveSchedule($course_type, $course_id, Request $request){
        $this->validateRequest([
            'start_time' => 'required',
            'end_time' => 'required',
            'lesson_date' => 'required|date_format:d/m/Y',
            'teacher_main_id' => 'required|exists:el_training_teacher,id',
            'teach_id' => 'nullable|exists:el_training_teacher,id',
            'cost_teacher_main' => 'nullable|min:0',
            'cost_teach_type' => 'nullable|min:0',
            'total_lessons' => 'required|numeric',

        ], $request, CoursePlanschedule::getAttributeName());

        $teacher_main_id = $request->input('teacher_main_id');
        $lesson_date = date_convert($request->input('lesson_date'));
        $start_time = $request->input('start_time') . ':00';
        $end_time = $request->input('end_time') . ':00';

        $check = CoursePlan::where('id', '=', $course_id)
            ->where('course_type', '=', $course_type)
            ->where(function ($sub) use ($lesson_date){
                $sub->orWhere('start_date', '>', $lesson_date);
                $sub->orWhere('end_date', '<', $lesson_date);
            })->exists();
        if ($check){
            json_message('Lịch học không nằm trong thời gian học', 'error');
        }

        $check_exist1 = CoursePlanschedule::where('lesson_date', '=', $lesson_date)
            ->where('start_time', '<=', $start_time)
            ->where('end_time', '>=', $start_time)
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', $course_type)
            ->where('teacher_main_id', '=', $teacher_main_id)
            ->exists();

        if ($check_exist1){
            json_message('Giờ học đã tồn tại', 'error');
        }

        $check_exist2 = CoursePlanschedule::where('lesson_date', '=', $lesson_date)
            ->where('start_time', '<=', $end_time)
            ->where('end_time', '>=', $end_time)
            ->where('course_id', '=', $course_id)
            ->where('course_type', '=', $course_type)
            ->where('teacher_main_id', '=', $teacher_main_id)
            ->exists();

        if ($check_exist2){
            json_message('Giờ học đã tồn tại', 'error');
        }

        if(get_date($start_time, 'H') >= get_date($end_time, 'H') && get_date($start_time, 'i') >= get_date($end_time, 'i')){
            json_message('Giờ kết thúc phải sau Giờ bắt đầu', 'error');
        }

        if ($request->input('teacher_main_id') == $request->input('teach_id')){
            json_message('Giảng viên chính và phụ không được trùng', 'error');
        }

        $model = new CoursePlanschedule();
        $model->start_time = $start_time;
        $model->end_time = $end_time;
        $model->lesson_date = $lesson_date;
        $model->teacher_main_id = $request->input('teacher_main_id');
        $model->teach_id = $request->input('teach_id');
        $model->cost_teacher_main = $request->input('cost_teacher_main');
        $model->cost_teach_type = $request->input('cost_teach_type');
        $model->total_lessons = $request->input('total_lessons');
        $model->course_id = $course_id;
        $model->course_type = $course_type;

        if ($model->save()) {
            json_result([
                'status' => 'success',
                'message' => 'Thêm thành công',
            ]);
        }
    }

    public function getSchedule($course_type, $course_id, Request $request){
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CoursePlanschedule::query();
        $query->select(['a.*', 'b.name as main_name', 'c.name as teach_name']);
        $query->from('el_course_plan_schedule AS a');
        $query->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_main_id');
        $query->leftJoin('el_training_teacher AS c', 'c.id', '=', 'a.teach_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.course_type', '=', $course_type);

        $count = $query->count();
        $query->orderBy('a.lesson_date', 'ASC');
        $query->orderBy('a.start_time', 'ASC');
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->start_time = get_date($row->start_time, 'H:i') . ' ' . get_date($row->lesson_date, 'd/m/Y');
            $row->end_time = get_date($row->end_time, 'H:i') . ' ' . get_date($row->lesson_date, 'd/m/Y');
            $row->cost_teacher_main = number_format($row->total_lessons * $row->cost_teacher_main, 0). ' VNĐ';
            $row->cost_teach_type = number_format($row->total_lessons * $row->cost_teach_type, 0) . ' VNĐ';
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function removeSchedule($course_type, $course_id, Request $request){
        $this->validateRequest([
            'ids' => 'required',
        ], $request);

        $item = $request->input('ids');
        CoursePlanschedule::destroy($item);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function getChild($course_type, $course_id, Request $request){
        $unit_id = $request->id;
        $unit = Unit::find($unit_id);

        $childs = Unit::where('parent_code', '=', $unit->code)->get(['id', 'name', 'code']);

        $count_child = [];
        $page_child = [];
        foreach ($childs as $item){
            $count_child[$item->id] = Unit::countChild($item->code);
            $page_child[$item->id] = route('module.course_plan.get_tree_child', ['course_type' => $course_type, 'id' => $course_id, 'parent_code' => $unit->code]);
        }

        $data = ['childs' => $childs, 'count_child' => $count_child, 'page_child' => $page_child];
        return \response()->json($data);
    }

    public function getTreeChild($course_type, $course_id, Request $request){
        $parent_code = $request->parent_code;
        return view('courseplan::backend.tree_unit_child', [
            'parent_code' => $parent_code
        ]);
    }

    public function convert(Request $request){
        $course_type = $request->course_type;
        $course_id = $request->course_id;

        $course_plan = CoursePlan::query()->find($course_id);
        $commit_plan = $course_plan->commit ? $course_plan->commit : 0;
        $date = current_datetime_sql();
        $subject = Subject::find($course_plan->subject_id);
        $user_role = UserRole::query()
            ->from('el_user_role as a')
            ->leftJoin('el_roles as b', 'b.id', '=', 'a.role_id')
            ->where('a.user_id', '=', profile()->user_id)
            ->first('b.code');

        if ($course_type == 1){
            $online_course = OnlineCourse::where('subject_id', '=', $subject->id)->count();
            $count_course = $online_course + 1;
            if (strlen($count_course) == 1){
                $count_course = '00'.$count_course;
            }
            if (strlen($count_course) == 2){
                $count_course = '0'.$count_course;
            }

            $online_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count_course;
            $course_plan->update([
                'code' => $online_code,
                'status_convert' => 1
            ]);

            $sql = CoursePlan::selectRaw('code, name, auto, unit_id, moodlecourseid, 0, image, start_date, end_date, '. profile()->user_id .', '. profile()->user_id .', category_id, description, training_program_id, level_subject_id, subject_id, plan_detail_id, 1, training_form_id, register_deadline, content, document, course_time, num_lesson, 2, views, action_plan, plan_app_template, plan_app_day, cert_code, has_cert, rating, template_id, unit_by, max_grades, min_grades, training_object_id, is_limit_time, start_timeday, end_timeday, '.$date.', '.$date.', 1')->where('id', '=', $course_id);

            OnlineCourse::query()->insertUsing(['code', 'name', 'auto', 'unit_id', 'moodlecourseid', 'isopen','image', 'start_date', 'end_date', 'created_by', 'updated_by', 'category_id', 'description', 'training_program_id', 'level_subject_id', 'subject_id', 'plan_detail_id', 'in_plan', 'training_form_id', 'register_deadline','content', 'document', 'course_time', 'num_lesson', 'status', 'views', 'action_plan','plan_app_template', 'plan_app_day', 'cert_code', 'has_cert','rating','template_id','unit_by', 'max_grades', 'min_grades', 'training_object_id', 'is_limit_time', 'start_timeday', 'end_timeday', 'created_at', 'updated_at', 'convert_course_plan'], $sql);

            $online = OnlineCourse::query()->orderByDesc('id')->first();

            $sql_course_object = CoursePlanObject::selectRaw($online->id .', title_id, unit_id, unit_level, type, '. profile()->user_id .', '. profile()->user_id .', '.$date.', '.$date.'')->where('course_id', '=', $course_id);
            if (count($sql_course_object->get()) > 0){
                OnlineObject::query()->insertUsing(['course_id','title_id','unit_id','unit_level','type','created_by','updated_by','created_at', 'updated_at'], $sql_course_object);
            }

            $sql_course_cost = CoursePlanCost::selectRaw($online->id .', cost_id, plan_amount, actual_amount, notes, '.$date.', '.$date.'')->where('course_id', '=', $course_id);
            if (count($sql_course_cost->get()) > 0){
                OnlineCourseCost::query()->insertUsing(['course_id','cost_id','plan_amount','actual_amount','notes','created_at', 'updated_at'], $sql_course_cost);
            }

        }else{
            $offline_course = OfflineCourse::where('subject_id', '=', $subject->id)->count();
            $count_course = $offline_course + 1;
            if (strlen($count_course) == 1){
                $count_course = '00'.$count_course;
            }
            if (strlen($count_course) == 2){
                $count_course = '0'.$count_course;
            }
            $offline_code = $subject->code.'_'.($user_role ? $user_role->code.'_' : '').date('y').'_'.$count_course;
            $course_plan->update([
                'code' => $offline_code,
                'status_convert' => 1
            ]);

            $sql = CoursePlan::selectRaw('code, name, unit_id, 1, training_form_id, plan_detail_id, description, 0, 2, start_date, end_date, register_deadline, image, max_student, document, '. profile()->user_id .', '. profile()->user_id .', training_program_id, level_subject_id, subject_id, training_location_id, training_unit, training_area_id, training_partner_id, content, views, category_id, course_time, num_lesson, action_plan, plan_app_template, plan_app_day, cert_code, has_cert, teacher_id, rating, template_id, coefficient, cost_class, quiz_id, unit_by, max_grades, min_grades, course_employee, course_action, training_object_id, teacher_type_id, training_type_id, '.$date.', '.$date.', training_partner_type, training_unit_type, '.$commit_plan.', commit_date, 1')->where('id', '=', $course_id);

            OfflineCourse::query()->insertUsing(['code', 'name', 'unit_id', 'in_plan', 'training_form_id', 'plan_detail_id', 'description', 'isopen', 'status', 'start_date', 'end_date', 'register_deadline', 'image', 'max_student', 'document', 'created_by', 'updated_by', 'training_program_id', 'level_subject_id', 'subject_id','training_location_id', 'training_unit', 'training_area_id', 'training_partner_id', 'content', 'views', 'category_id','course_time', 'num_lesson', 'action_plan', 'plan_app_template','plan_app_day','cert_code','has_cert', 'teacher_id', 'rating', 'template_id', 'coefficient', 'cost_class', 'quiz_id', 'unit_by', 'max_grades', 'min_grades', 'course_employee', 'course_action', 'training_object_id', 'teacher_type_id', 'training_type_id', 'created_at', 'updated_at','training_partner_type','training_unit_type','commit','commit_date','convert_course_plan'], $sql);

            $offline = OfflineCourse::query()->orderByDesc('id')->first();

            OfflineCourseClass::firstOrCreate(['course_id'=>$offline->id,'default'=>1],[
                'name' => 'Lớp mặc định',
                'default' => 1,
                'students' => $offline->max_student,
                'code' => $offline->code.'_001',
                'start_date' => $offline->start_date,
                'end_date' => $offline->end_date
            ]);

            $sql_course_object = CoursePlanObject::selectRaw($offline->id .', title_id, unit_id, unit_level, type, '. profile()->user_id .', '. profile()->user_id .', '.$date.', '.$date.'')->where('course_id', '=', $course_id);
            if (count($sql_course_object->get()) > 0){
                OfflineObject::query()->insertUsing(['course_id','title_id','unit_id','unit_level','type','created_by','updated_by','created_at', 'updated_at'], $sql_course_object);
            }

            $sql_course_teacher = CoursePlanTeacher::selectRaw($offline->id .', teacher_id, '.$date.', '.$date.'')->where('course_id', '=', $course_id);
            if (count($sql_course_teacher->get()) > 0){
                OfflineTeacher::query()->insertUsing(['course_id', 'teacher_id','created_at', 'updated_at'], $sql_course_teacher);
            }

            $sql_course_schedule = CoursePlanschedule::selectRaw($offline->id .', start_time, end_time, lesson_date, teacher_main_id, teach_id, cost_teacher_main, cost_teach_type, total_lessons, '.$date.', '.$date.'')->where('course_id', '=', $course_id);

            if (count($sql_course_schedule->get()) > 0){
                OfflineSchedule::query()->insertUsing(['course_id', 'start_time', 'end_time', 'lesson_date', 'teacher_main_id', 'teach_id', 'cost_teacher_main', 'cost_teach_type', 'total_lessons','created_at', 'updated_at'], $sql_course_schedule);
            }

            $sql_course_cost = CoursePlanCost::selectRaw($offline->id .', cost_id, plan_amount, actual_amount, notes, '.$date.', '.$date.'')->where('course_id', '=', $course_id);
            if (count($sql_course_cost->get()) > 0){
                OfflineCourseCost::query()->insertUsing(['course_id','cost_id','plan_amount','actual_amount','notes','created_at', 'updated_at'], $sql_course_cost);
            }

            $sql_course_condition = CoursePlanCondition::selectRaw($offline->id .', ratio, minscore, survey, certificate, '.$date.', '.$date.'')->where('course_id', '=', $course_id);
            if (count($sql_course_condition->get()) > 0){
                OfflineCondition::query()->insertUsing(['course_id','ratio','minscore','survey','certificate','created_at', 'updated_at'], $sql_course_condition);
            }
        }

        json_message('Chuyển đổi thành công');
    }

    public function teacher($course_type, $course_id) {
        $course = CoursePlan::find($course_id);
        $page_title = $course->name;
        $teachers = TrainingTeacher::get();

        return view('courseplan::backend.offline.teacher', [
            'page_title' => $page_title,
            'course' => $course,
            'teachers' => $teachers,
            'course_type' => $course_type
        ]);
    }

    public function getDataTeacher($course_type, $course_id, Request $request) {
        $search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = CoursePlanTeacher::query();
        $query->select(['a.*', 'b.name as teacher_name', 'b.email as teacher_email', 'b.phone as teacher_phone']);
        $query->from('el_course_plan_teacher AS a');
        $query->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.course_type', '=', $course_type);

        if ($search) {
            $query->where('b.name', 'like', '%'. $search .'%');
        }

        $count = $query->count();
        $query->orderBy('b.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function saveTeacher($course_type, $course_id, Request $request) {
        $this->validateRequest([
            'teacher_id' => 'required|exists:el_training_teacher,id',
        ], $request, CoursePlanTeacher::getAttributeName());

        $teacher_id = $request->input('teacher_id');

        if(CoursePlanTeacher::checkExists($course_type, $course_id, $teacher_id)){
            json_message('Giảng viên đã tồn tại', 'error');
        }
        $model = new CoursePlanTeacher();
        $model->teacher_id = $teacher_id;
        $model->course_id = $course_id;
        $model->course_type = $course_type;

        if ($model->save()) {
            $redirect = route('module.course_plan.teacher', ['course_type' => $course_type, 'id' => $course_id]);
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => $redirect,
            ]);
        }

        json_message(trans('laother.save_error'), 'error');
    }

    public function removeTeacher($course_type, $course_id, Request $request) {
        $ids = $request->input('ids', null);
        CoursePlanTeacher::destroy($ids);
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function ajaxGetUnit(Request $request) {
        $unit_type = $request->unit_type;
        $unit = Unit::where('type',$unit_type)->get();
        json_result($unit);
    }

    public function viewRegisterTrainingPlan(Request $request){
        return view('courseplan::backend.register_training_plan');
    }

    public function getDataRegisterTrainingPlan(Request $request)
    {
        $search = $request->input('search');
        $training_program_id = $request->input('training_program_id');
        $level_subject_id = $request->input('level_subject_id');
        $subject_id = $request->input('subject_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $course_belong_to = $request->course_belong_to;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = RegisterTrainingPlan::query();
        $query->select([
            'el_register_training_plan.*',
            'c.name AS subject_name',
            'e.full_name',
            'e.unit_name',
        ]);
        $query->from('el_register_training_plan');
        $query->leftJoin('el_subject AS c', 'c.id', '=', 'el_register_training_plan.subject_id');
        $query->leftJoin('el_profile_view as e', 'e.user_id', '=', 'el_register_training_plan.created_by');
        $query->where('el_register_training_plan.send', '=', 1);

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

        if($course_belong_to){
            $query->where('el_register_training_plan.course_belong_to', '=', $course_belong_to);
        }

        $count = $query->count();
        $query->orderBy('el_register_training_plan.'.$sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $training_form = TrainingForm::find($row->training_form_id);
            $row->training_form = $training_form ? $training_form->name : '';
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
            $row->created_at2 = get_date($row->created_at, 'H:i d/m/Y');
            $row->course_employee = $row->course_employee == 1 ? 'Tân tuyển' : 'Hiện hữu';

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
            $row->course_time = $course_time ? $course_time.' '.$text_course_time_unit : '';

            if($row->training_area_id){
                $training_area = Area::whereIn('id', json_decode($row->training_area_id))->pluck('name')->toArray();
                $row->training_area = implode('; ', $training_area);
            }

            if($row->teacher_id){
                $user_teacher = ProfileView::whereIn('user_id', json_decode($row->teacher_id))->pluck('full_name')->toArray();
                $row->teachers = implode('; ', $user_teacher);
            }
            $row->sub_target = sub_char($row->target, 10);
            $row->sub_content = sub_char($row->content, 10);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function approveRegisterTrainingPlan(Request $request)
    {
        $date = current_datetime_sql();

        $ids = $request->input('ids', null);
        $status = $request->input('status', null);
        $note = $request->input('note', null);

        foreach($ids as $id){
            $check_status = RegisterTrainingPlan::where('id', $id)->where('status', 1);
            if($check_status->exists()){
                json_message('Kế hoạch đã duyệt', 'warning');
            }else{
                RegisterTrainingPlan::where('id', $id)->update([
                    'status' => $status,
                    'note_status' => $note,
                ]);
            }
        }

        $user_id = RegisterTrainingPlan::whereIn('id', $ids)->pluck('created_by')->toArray();

        if($status == 0) {
            $notify = new Notify();
            $notify->subject = 'Thông báo đã từ chối đề xuất kế hoạch đào tạo tháng';
            $notify->content = 'Kế hoạch đào tạo tháng của bạn đã bị từ chối. Lý do: '.$note;
            $notify->users = $user_id;
            $notify->addMultiNotify();

            json_message('Đã từ chối','success');
        } else {
            $notify = new Notify();
            $notify->subject = 'Thông báo đã phê duyệt đề xuất kế hoạch đào tạo tháng';
            $notify->content = 'Kế hoạch đào tạo tháng của bạn đã được phê duyệt';
            $notify->users = $user_id;
            $notify->addMultiNotify();

            $models = RegisterTrainingPlan::whereIn('id', $ids)->get();
            foreach($models as $model){
                $sql = RegisterTrainingPlan::selectRaw('course_type, training_program_id, level_subject_id, subject_id, name, start_date, end_date, course_time, training_form_id, training_area_id, course_employee, max_student, created_by, updated_by, unit_by, status, course_belong_to, '.$date.', '.$date.'')->where('id', $model->id);

                \DB::table('el_course_plan')->insertUsing(['course_type', 'training_program_id', 'level_subject_id', 'subject_id', 'name', 'start_date', 'end_date', 'course_time', 'training_form_id', 'training_area_id', 'course_employee', 'max_student', 'created_by', 'updated_by', 'unit_by', 'status', 'course_belong_to', 'created_at', 'updated_at'], $sql);

                if($model->teacher_id){
                    $course_plan = CoursePlan::orderBy('id', 'desc')->first();

                    $teachers_array = json_decode($model->teacher_id);
                    foreach($teachers_array as $teacher_id){
                        $training_teacher = TrainingTeacher::where('user_id', $teacher_id)->first();

                        if(!$training_teacher){
                            $user = Profile::where('user_id', '=', $teacher_id)->first();

                            $training_teacher = new TrainingTeacher();
                            $training_teacher->user_id = $user->user_id;
                            $training_teacher->code = $user->code;
                            $training_teacher->name = $user->lastname .' '. $user->firstname;
                            $training_teacher->phone = $user->phone;
                            $training_teacher->email = $user->email;
                            $training_teacher->save();
                        }

                        $course_plan_teacher = CoursePlanTeacher::firstOrNew(['teacher_id' => $training_teacher->id, 'course_id' => $course_plan->id, 'course_type' => $course_plan->course_type]);
                        $course_plan_teacher->teacher_id = $training_teacher->id;
                        $course_plan_teacher->course_id = $course_plan->id;
                        $course_plan_teacher->course_type = $course_plan->course_type;
                        $course_plan_teacher->save();
                    }
                }

            }

            json_message('Duyệt thành công','success');
        }
    }

    public function exportRegisterTrainingPlan($course_type)
    {
        if($course_type == 1){
            return (new RegisterTrainingPlanOnline())->download('ke_hoach_dao_tao_thang_'. date('d_m_Y') .'.xlsx');
        }else{
            return (new RegisterTrainingPlanOffline())->download('ke_hoach_dao_tao_thang_'. date('d_m_Y') .'.xlsx');
        }
    }
}
