<?php

namespace App\Http\Controllers\Mobile;

use App\Models\Permission;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeacher;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        return view('themes.mobile.frontend.attendance.index', [
            'list' => $this->getData($request),
        ]);
    }

    public function getData(Request $request)
    {
        $type = $request->get('type');
        $search = trim($request->input('search'));
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $sort = $request->input('sort', 'start_date');
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

        if ($type && $type == 1){
            $query->whereNotExists(function ($sub){
                $sub->select(['register_id'])
                    ->from('el_offline_attendance')
                    ->leftJoin('el_offline_register as register', 'register.id', '=', 'register_id')
                    ->whereColumn('register.course_id', '=', 'el_offline_course.id');
            });
        }

        if ($type && $type == 2){
            $query->whereExists(function ($sub){
                $sub->select(['register_id'])
                    ->from('el_offline_attendance')
                    ->leftJoin('el_offline_register as register', 'register.id', '=', 'register_id')
                    ->whereColumn('register.course_id', '=', 'el_offline_course.id');
            });
        }
        if ($type && $type == 3){
            $query->where(function ($subquery){
                $subquery->whereNotNull('end_date');
                $subquery->where('end_date', '<', date('Y-m-d H:i:s'));
            });
        }

        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        return $rows;
    }

    public function showStudents(Request $request, $course_id)
    {
        $schedule_id = $request->input('schedule',0);
        $course = OfflineCourse::find($course_id);
        $schedules = OfflineSchedule::getSchedules($course_id);
        $schedule_id = $schedule_id>0?$schedule_id: OfflineSchedule::getMinSchedules($course_id);

        $total_register = OfflineRegister::where('course_id', '=', $course_id)->count();

        $total_attendance = OfflineAttendance::query()
            ->leftJoin('el_offline_register as register', 'register.id', '=', 'el_offline_attendance.register_id')
            ->where('register.course_id', '=', $course_id)
            ->count();

        $teacher = OfflineTeacher::select(['b.name', 'b.code']);
        $teacher->from('el_offline_course_teachers AS a');
        $teacher->leftJoin('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id');
        $teacher->where('a.course_id', '=', $course_id);
        $teacher = $teacher->get();

        return view('themes.mobile.frontend.attendance.attendance', [
            'course' => $course,
            'schedules' => $schedules,
            'schedule_id' => $schedule_id,
            'total_register' => $total_register,
            'total_attendance' => $total_attendance,
            'teacher' => $teacher
        ]);
    }
    public function getStudents(Request $request,$course_id)
    {
        $schedule_id = $request->input('schedule',0);
        $sort = $request->input('sort', 'full_name');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);
        $schedule_id = $schedule_id?$schedule_id: OfflineSchedule::getMinSchedules($course_id);
        $query = OfflineRegister::query();
        $query->select(['b.id','c.code','c.full_name','c.title_name','c.unit_name', 'c.user_id'])
            ->from('el_offline_register as a')
            ->leftJoin('el_offline_attendance as b','a.id','=','b.register_id')
            ->leftJoin("el_profile_view as c",'c.user_id','=','a.user_id')
            ->where('a.course_id', '=', $course_id)
            ->where('b.status', '=', 1)
            ->where('b.schedule_id', '=', $schedule_id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row){
            $row->img_user = Profile::avatar($row->user_id);

            $row->url_modal_image_user = route('theme.mobile.frontend.attendance.show_modal_image_user', ['user_id' => $row->user_id]);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
    public function showModal(Request $request) {
        $schedule_id = $request->input('schedule_id');
        $course_id = $request->input('course_id');
        return view('attendance::frontend.qrcode', [
            'schedule_id' => $schedule_id,
            'course_id' => $course_id
        ]);
    }

    public function showModalImageUser($user_id){
        $img_user = Profile::avatar($user_id);

        return view('themes.mobile.frontend.attendance.image_user', [
            'img_user' => $img_user,
        ]);
    }
}
