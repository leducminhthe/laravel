<?php
namespace App\Http\Controllers\Backend;

use App\Exports\TrainingTeacherExport;
use App\Imports\ImportTrainingTeacher;
use App\Jobs\NotifyUserOfCompletedImportSubject;
use App\Models\Categories\Titles;
use App\Models\Categories\TrainingPartner;
use App\Models\Categories\Unit;
use App\Models\Notifications;
use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\Absent;
use App\Models\Categories\AbsentReason;
use App\Models\Categories\Area;
use App\Models\Categories\Discipline;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TrainingTeacherRegister;
use App\Models\Categories\TeacherType;
use App\Models\Categories\TrainingTeacherCertificate;
use App\Models\Categories\TrainingTeacherHistory;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileView;
use Illuminate\Support\Str;
use Modules\ReportNew\Entities\ReportNewExportBC11;
use Modules\Offline\Entities\OfflineCourse;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeacher;
use Carbon\Carbon;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use App\Models\UserRole;
use Modules\Offline\Entities\OfflineTeacherClass;

class TrainingTeacherController extends Controller
{
    public function index() {
        $notifications = Notifications::where('notifiable_id', '=', profile()->user_id)
            ->where('notifiable_type', '=', 'App\Models\User')
            ->whereNull('read_at')
            ->get();
        \Session::forget('errors');

        $teacher_types = TeacherType::get();
        $training_partner = TrainingPartner::get();
        $user_id = profile()->user_id;
        $model = Profile::query();
        $model->select([
            'a.id',
            'a.user_id',
            'a.code',
            'a.lastname',
            'a.firstname',
        ])->disableCache();
        $model->from('el_profile as a');
        $model->where('a.user_id', '>', 2);
        $model->whereNotIn('a.user_id', function($sub) {
            $sub->select(['user_id']);
            $sub->from('el_training_teacher');
            $sub->whereNotNull('user_id');
            $sub->pluck('user_id')->toArray();
        });
        $profile = $model->get();

        return view('backend.category.training_teacher.index', [
            'notifications' => $notifications,
            'teacher_types' => $teacher_types,
            'training_partner' => $training_partner,
            'get_users_not_regis' => $profile,
        ]);
    }

    public function getData(Request $request) {
        $search = $request->input('search');
        $type = $request->input('type');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        TrainingTeacher::addGlobalScope(new DraftScope());
        $query = TrainingTeacher::query();

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }
        if($type){
            $query->where('type', $type);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $partner = TrainingPartner::find($row->training_partner_id);
            $row->partner = $partner ? $partner->name : '';
            $row->edit_url = route('backend.category.training_teacher.edit', ['id' => $row->id]);

            $row->history_url = route('backend.category.training_teacher.history', [$row->id]);

            $row->rank = $this->getRank($row->id);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    private function getRank($teacher_id){
        $rank = [];
        $training_teacher = TrainingTeacher::whereStatus(1)->pluck('id')->toArray();
        foreach($training_teacher as $teacher){
            $history = OfflineTeacherClass::query()
            ->join('el_offline_course as course', 'course.id', '=', 'el_offline_teacher_class.course_id')
            ->join('el_training_teacher_history as history', function($join) {
                $join->on('history.course_id', '=', 'el_offline_teacher_class.course_id');
                $join->on('history.class_id', '=', 'el_offline_teacher_class.class_id');
                $join->on('history.teacher_id', '=', 'el_offline_teacher_class.teacher_id');
            })
            ->where('el_offline_teacher_class.teacher_id', $teacher)
            ->sum('history.num_hour');

            $rank[$teacher] = $history;
        }
        arsort($rank);
        $i = 0;
        foreach($rank as $key => $value){
            $i += 1;
            $rank[$key] = $i. '<br> ('.$value .' '. trans('latraining.hour') .')';
        }

        return $rank[$teacher_id];
    }

    public function form(Request $request) {
        $model = TrainingTeacher::findOrFail($request->id);
        $user = Profile::find($model->user_id);
        $unit = Unit::where('code', '=', @$user->unit_code)->first();
        $title = Titles::where('code', '=', @$user->title_code)->first();
        $page_title = $model->id ? $model->name : trans('labutton.add_new');
        $training_partner = TrainingPartner::get();
        $teacher_types = TeacherType::get();
        return view('backend.category.training_teacher.form', [
            'model' => $model,
            'page_title' => $page_title,
            'unit' => $unit,
            'title' => $title,
            'training_partner' => $training_partner,
            'teacher_types' => $teacher_types
        ]);
    }

    public function save(Request $request) {
        $this->validateRequest([
            'type' => 'required_if:id,<>,|in:1,2',
            'code' => 'required|unique:el_training_teacher,code,'. $request->id,
            'name' => 'required',
            'status' => 'required|in:0,1',
        ], $request, TrainingTeacher::getAttributeName());

        $model = TrainingTeacher::firstOrNew(['id' => $request->id]);
        $model->fill($request->all());

        $name = $request->input('name');

        if(TrainingTeacher::checkExists($name)){
            TrainingTeacher::where('name', '=', $name)
            ->where('type', '=', $request->input('type'))
            ->update([
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'status' => $request->input('status'),
            ]);
        }

        if ($model->save()) {

            $report11 = ReportNewExportBC11::query()->where('training_teacher_id', $model->id);
            if ($report11->exists()){
                $report11->update([
                    'user_id' => $model->user_id,
                    'user_code' => $model->code,
                    'fullname' => $model->name,
                    'account_number' => $model->account_number
                ]);
            }

            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
            ]);
        }

        json_message(trans('laother.can_not_save'), 'error');
    }

    public function remove(Request $request) {
        $ids = $request->input('ids', null);
        $check_offline = OfflineTeacher::whereIn('teacher_id', $ids)->first();
        if (!empty($check_offline)) {
            json_message('Không thể xoá. Có dữ liệu liên quan khóa học offline', 'error');
        }
        TrainingTeacher::destroy($ids);
        TrainingTeacherCertificate::query()->whereIn('training_teacher_id', $ids)->delete();
        ReportNewExportBC11::query()->whereIn('training_teacher_id', $ids)->delete();
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }

    public function ajaxGetUser(Request $request)
    {
        $this->validateRequest([
            'ids' => 'required',
        ], $request);

        $ids = $request->input('ids');

        $model = TrainingTeacher::where('user_id', '=', $ids)->first();
        $user = Profile::where('user_id', '=', $ids)->first();
        $unit = Unit::where('code', '=', @$user->unit_code)->first();
        $title = Titles::where('code', '=', @$user->title_code)->first();

        json_result([
            'code' => $user->code,
            'name' => $user->lastname . ' ' . $user->firstname,
            'phone' => $model ? $model->phone : $user->phone,
            'email' => $user->email,
            'unit' => @$unit->code . ' - ' . @$unit->name,
            'title' => @$title->code . ' - ' . @$title->name,
        ]);
    }

    public function import(Request $request) {
        $this->validateRequest([
            'import_file' => 'required|file',
        ], $request, [
            'import_file' => ''
        ]);

        $file = $request->file('import_file');
        $type_import = $request->type_import;

        $import = new ImportTrainingTeacher(\Auth::user(), $type_import);
        \Excel::import($import, $request->file('import_file'));

        if ($import->errors) {
            session()->put('errors', $import->errors);
            session()->save();
        } else {
            json_result([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('backend.category.training_teacher')
            ]);
        }

        json_result([
            'status' => 'error',
            'message' => trans('laother.unable_upload'),
            'redirect' => route('backend.category.training_teacher')
        ]);
    }

    public function export()
    {
        return (new TrainingTeacherExport())->download('danh_sach_giang_vien_'. date('d_m_Y') .'.xlsx');
    }

    public function getDataSchedule(Request $request)
    {
        $user_id = $request->user_id;
        $result = [];
        $allCourse = $this->getAllCourse();
        foreach ($allCourse as $item){
            $schedule = OfflineSchedule::where('course_id', $item->id)->where(function ($subquery) use ($user_id){
                $subquery->orWhere('teacher_main_id', $user_id);
                $subquery->orWhere('teach_id', $user_id);
            })->get();

            foreach ($schedule as $key => $value) {
                $result[] = [
                    'title' => get_date($value->start_time,'H:i').' - '.$item->name,
                    'start' => get_date($value->lesson_date, 'Y-m-d'),
                    'description' => $item->name . ' (' . $item->code .')',
                ];
            }
        }
        return response()->json($result);
    }

    public function getAllCourse()
    {
        $query = OfflineCourse::query();
        $query->select(['el_offline_course.*']);
        $query->where('el_offline_course.status', '=', 1)
            ->where('el_offline_course.isopen', '=', 1);
        return $query->get();
    }

    public function listPermission(){
        $this->putRole();
        $profile = profile();
        return view('backend.category.training_teacher.list_permission', [
            'profile' => $profile
        ]);
    }

    public function listCourse(){
        return view('backend.category.training_teacher.list_course');
    }

    public function calendarTeacher()
    {
        $model = TrainingTeacher::where('user_id', profile()->user_id)->first();
        return view('backend.category.training_teacher.calendar',[
            'model' => $model
        ]);
    }

    // ĐĂNG KÝ GIẢNG DẠY
    public function registerTeach()
    {
        return view('backend.category.training_teacher.register');
    }

    // CHI TIẾT ĐĂNG KÝ
    public function detailRegisterTeach($id)
    {
        $model = OfflineCourse::find($id,['id','name']);
        return view('backend.category.training_teacher.register_detail',[
            'model' => $model
        ]);
    }

    // DỮ LIỆU KHÓA HỌC ĐĂNG KÝ GIẢNG DẠY
    public function getdataCourseRegister(Request $request){
        $date = date('Y-m-d');
        $trainingTeacherId = TrainingTeacher::where('user_id', profile()->user_id)->first(['id']);

        $search = trim($request->input('search'));
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineCourse::query();
        $query->select([
            'id',
            'code',
            'name',
            'status',
            'start_date',
            'end_date'
        ]);
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        $query->where('start_date', '>', $date);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }
        if ($start_date) {
            $query->where('start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('start_date', '<=', date_convert($end_date, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $trainingTeacherRegister = TrainingTeacherRegister::where('course_id', $row->id)->where('user_id', profile()->user_id)->first();
            $checkTeacherClass = OfflineTeacher::where('course_id', $row->id)->where('teacher_id', $trainingTeacherId->id)->exists();
            if($checkTeacherClass && empty($trainingTeacherRegister)) {
                $row->checkExistsTeacher = $checkTeacherClass;
            }
            if(!empty($trainingTeacherRegister)) {
                $row->checkApprove = $trainingTeacherRegister->approve;
            }
            $row->note = ($trainingTeacherRegister && $trainingTeacherRegister->note) ? $trainingTeacherRegister->note : '';
            $row->course_name = $row->name .' ('. $row->code .')';
            $row->course_date = get_date($row->start_date) .' => '. get_date($row->end_date);
            $row->num_schedule = OfflineCourseClass::whereCourseId($row->id)->count();
            $row->detail_register = route('backend.category.training_teacher.detail_register_teach', [$row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    // DỮ LIỆU CHI TIẾT KHÓA HỌC ĐĂNG KÝ GIẢNG DẠY
    public function getdataDetailRegisterTeach($course_id, Request $request){
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineCourseClass::where('course_id', '=', $course_id);
        $count = $query->count();
        $query->orderBy($sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $index => $row) {
            $row->start_date = get_date($row->start_date);
            $row->end_date = get_date($row->end_date);
        }
        json_result(['total' => $count, 'rows' => $rows]);
    }

    // LƯU ĐĂNG KÝ GIẢNG DẠY
    public function saveRegisterClass(Request $request)
    {
        $courseId = $request->courseId;
        $trainingTeacherId = TrainingTeacher::where('user_id', profile()->user_id)->first(['id']);
        $id = $request->id;

        $save = new TrainingTeacherRegister();
        $save->teacher_id = $trainingTeacherId->id;
        $save->course_id = $courseId;
        $save->user_id = profile()->user_id;
        if($save->save()) {
            $role = UserRole::query();
            $role->from('el_user_role as a');
            $role->join('el_role_has_permissions as b', 'b.role_id', '=', 'a.role_id');
            $role->join('el_permissions as c', function($join){
                $join->on('c.id', '=', 'b.permission_id');
                $join->where('c.name', 'training-teacher-register');
            });
            $userRole = $role->pluck('a.user_id')->toArray();
            foreach($userRole as $user) {
                $query = new Notify();
                $query->user_id = $user;
                $query->subject = 'Duyệt đăng ký giảng dạy';
                $query->content = 'Giảng viên '. Profile::fullname(profile()->user_id) .' đăng ký giảng dạy. Vui lòng vào quản trị để duyệt';
                $query->url = route('backend.approve_teacher_register');
                $query->created_by = 0;
                $query->save();

                $content = \Str::words(html_entity_decode(strip_tags($query->content)), 10);
                $redirect_url = route('module.notify.view', [
                    'id' => $query->id,
                    'type' => 1
                ]);

                $notification = new AppNotification();
                $notification->setTitle($query->subject);
                $notification->setMessage($content);
                $notification->setUrl($redirect_url);
                $notification->add($user);
                $notification->save();
            }
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
        ]);
    }

    // DANH SÁCH CÁC KHÓA HỌC ĐÃ GIẢNG DẠY/ ĐANG GIẢNG DẠY
    public function listCourseTeacher()
    {
        $model = TrainingTeacher::where('user_id', profile()->user_id)->first();
        return view('backend.category.training_teacher.list_course_teacher',[
            'model' => $model
        ]);
    }

    // DỮ LIỆU CÁC KHÓA HỌC ĐÃ GIẢNG DẠY/ĐANG GIẢNG DẠY
    public function listCourseTeacherGetData($type, Request $request)
    {
        $search = trim($request->input('search'));
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $trainingTeacher = TrainingTeacher::where('user_id', profile()->user_id)->first(['id']);

        $query = OfflineCourse::query();
        $query->select(['id','code','name','status','start_date','end_date']);
        $query->where('status', '=', 1);
        $query->where('isopen', '=', 1);
        $query->whereIn('id', function ($subquery2) use ($trainingTeacher) {
            $subquery2->select(['course_id'])
                ->from('el_offline_schedule')
                ->where('teacher_main_id', $trainingTeacher->id)
                ->orWhere('teach_id', 'like', '%'. $trainingTeacher->id . '%');
        });

        if($type == 1) {
            $query->where('start_date', '<=', now());
            $query->where('end_date', '>=', now());
        } else {
            $query->where('end_date', '<=', now());
        }

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }
        if ($start_date) {
            $query->where('start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('start_date', '<=', date_convert($end_date, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->course_name = $row->name .' ('. $row->code .')';
            $row->course_date = get_date($row->start_date) .' => '. get_date($row->end_date);
            $row->info_url = route('module.offline.modal_info', ['course_id' => $row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function listCourseData(Request $request){
        $search = trim($request->input('search'));
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $teacher = OfflineTeacher::query()
            ->select(['a.course_id'])
            ->from('el_offline_course_teachers AS a')
            ->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id')
            ->where('b.user_id', '=', profile()->user_id)
            ->pluck('a.course_id')->toArray();

        $query = OfflineCourse::query();
        $query->select(['id','code','name','status','start_date','end_date'])
            ->where('status', '=', 1)
            ->where('start_date', '<=', now());

        if (!Permission::isAdmin()){
            $query->whereIn('id', $teacher);
        }
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('code', 'like', '%'. $search .'%');
                $subquery->orWhere('name', 'like', '%'. $search .'%');
            });
        }
        if ($start_date) {
            $query->where('start_date', '>=', date_convert($start_date));
        }

        if ($end_date) {
            $query->where('start_date', '<=', date_convert($end_date, '23:59:59'));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->course_name = $row->name .' ('. $row->code .')';
            $row->course_date = get_date($row->start_date) .' => '. get_date($row->end_date);
            $row->num_register = $row->countUserRegister();
            $row->num_schedule = OfflineSchedule::whereCourseId($row->id)->count();

            $row->total_attendance = OfflineAttendance::whereCourseId($row->id)->groupBy('user_id')->get('user_id')->count();
            $row->attendance_url = route('backend.category.training_teacher.attendance_user', [$row->id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function attendanceUser($course_id, Request $request){
        $course = OfflineCourse::find($course_id);
        $offlineCourseClass = OfflineCourseClass::where('course_id', $course_id)->first();
        $class_id = $offlineCourseClass->id;

        $schedule = $request->schedule;
        $schedules = OfflineSchedule::getSchedulesOffline($course_id,$class_id);

        $num_register = $course->countUserRegister();
        $total_attendance = OfflineAttendance::whereCourseId($course_id)->groupBy('user_id')->get('user_id')->count();
        $profile_teacher = profile();
        $qrcode_attendance = route('qrcode_process',['course'=>$course_id,'class_id'=>$class_id,'schedule'=>$schedule,'type'=>'attendance']);
        return view('backend.category.training_teacher.attendance_user', [
            'course' => $course,
            'class_id' => $class_id,
            'schedule' => $schedule,
            'schedules' => $schedules,
            'num_register' => $num_register,
            'profile_teacher' => $profile_teacher,
            'total_attendance' => $total_attendance,
            'qrcode_attendance' => $qrcode_attendance,
            'offlineCourseClass' => $offlineCourseClass,
        ]);
    }

    public function attendanceUserData($course_id, Request $request){
        $search = $request->input('search');
        $title = $request->input('title');
        $unit = $request->input('unit');
        $status = $request->input('status');
        $schedule = $request->schedule;

        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineRegister::query();
        $query->select([
            'a.*',
            'b.lastname',
            'b.firstname',
            'b.code',
            'c.name AS title_name',
        ]);
        $query->from('el_offline_register AS a');
        $query->join('el_profile AS b', 'b.user_id', '=', 'a.user_id');
        $query->leftJoin('el_titles AS c', 'c.code', '=', 'b.title_code');
        $query->where('a.course_id', '=', $course_id);
        $query->where('a.status', '=', 1);

        $count = $query->count();
        $query->orderBy('a.'.$sort, 'asc');
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();

        $absentList = Absent::get();
        $absentReasonList = AbsentReason::get();
        $disciplineList = Discipline::get();

        foreach($rows as $row) {
            $attendan = OfflineAttendance::checkExists($row->id, $schedule);
            $row->percent = $attendan ? $attendan->percent : '';
            $row->time_attendance = $attendan ? get_date($attendan->created_at) : '';

            $discipline ='<select data-regid="'.$row->id.'" class="form-control discipline w-100" data-placeholder="-- '. trans('latraining.violation') .' --">';
            $discipline .='<option>'. trans('latraining.choose') .'</option>';
            foreach($disciplineList as $v){
                $discipline .='<option'.($attendan && $attendan->discipline_id == $v->id ? ' selected' : '').' value="'.$v->id.'">'.$v->name.'</option>';
            }
            $discipline .='</select>';
            $row->discipline =  $discipline;

            $absent ='<select data-regid="'.$row->id.'" class="form-control absent" data-placeholder="-- '. trans('latraining.absent') .' --">';
            $absent .='<option>'. trans('latraining.choose') .'</option>';
            foreach($absentList as $v){
                $absent .='<option'.($attendan && $attendan->absent_id == $v->id ? ' selected' : '').' value="'.$v->id.'">'.$v->name.'</option>';
            }
            $absent .='</select>';
            $row->absent = $absent;

            $absent_reason ='<select data-regid="'.$row->id.'" class="form-control absent_reason" data-placeholder="-- '. trans('latraining.reason_absence') .' --">';
            $absent_reason .='<option>'. trans('latraining.choose') .'</option>';
            foreach($absentReasonList as $v){
                $absent_reason .='<option'.($attendan && $attendan->absent_reason_id == $v->id ? ' selected' : '').' value="'.$v->id.'">'.$v->name.'</option>';
            }
            $absent_reason .='</select>';
            $row->absent_reason = $absent_reason;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }

    public function qrcodeProcess(Request $request)
    {
        $x = OfflineAttendance::updateAttendance($request->user, $request->course, $request->schedule,$request->class_id, '2.GVQRC');
        $profile = ProfileView::whereUserId($request->user)->first();
        $time_attendance = date('H:i d/m/Y');

        if ($x){
            $count_schedule = OfflineSchedule::whereCourseId($request->course)->count();
            $count_attendance = OfflineAttendance::whereUserId($request->user)->where('course_id', '=', $request->course)->count();

            if ($count_attendance >= $count_schedule){
                \Artisan::call('command:offline_complete '.$request->user .' '.$request->course);
            }

            $total_attendance = OfflineAttendance::where('course_id', '=', $request->course)->groupBy('user_id')->get('user_id')->count();
            \Session::put('info_attendance',
                [
                    'status'=>'success',
                    'success' => 'Đã tham gia',
                    'profile' => $profile,
                    'total_attendance' => $total_attendance,
                    'time_attendance' => $time_attendance,
                ]
            );
            return redirect()
                ->route('backend.category.training_teacher.attendance_user',['course_id'=>$request->course,'class_id'=>$request->class_id]+['schedule'=>$request->schedule]);
        }else{
            \Session::put('info_attendance',
                [
                    'status'=>'error',
                    'error' => 'Ố ô, Bạn không nằm trong danh sách lớp học. Hãy liên hệ Giáo viên/Phục trách đào tạo',
                    'profile' => $profile,
                    'time_attendance' => $time_attendance,
                ]
            );
            return redirect()
                ->route('backend.category.training_teacher.attendance_user',['course_id'=>$request->course,'class_id'=>$request->class_id]+['schedule'=>$request->schedule]);
        }
    }

    // Lịch sử giảng dạy (Quyền Giảng Viên)
    public function historyTeacher(){
        $training_teacher = TrainingTeacher::whereUserId(profile()->user_id)->first();
        return view('backend.category.training_teacher.history_teacher',[
            'training_teacher' => $training_teacher,
        ]);
    }
    private function putRole(){
        $hasPermissionTeacher = \App\Models\Permission::isTeacher();
        if (!$hasPermissionTeacher)
            return abort('403', 'Permission denied !');
        \session()->put('user_role','teacher');
        \session()->save();
    }
    public function getClassByCourse($course_id, Request $request)
    {
        $teacher_id=TrainingTeacher::whereUserId(profile()->user_id)->value('id');
        $class = \DB::table('offline_course_class as a')->join('el_offline_teacher_class as b',function ($subQuery){
            $subQuery->on('a.id','=','b.class_id');
            $subQuery->on('a.course_id','=','b.course_id');
        })->where(['a.course_id'=>$course_id,'b.teacher_id'=>$teacher_id])->select('a.*')->get();
        return json_result([
            'status' => 'ok',
            'class' => $class,
        ]);
    }
}
