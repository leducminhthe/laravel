<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CourseView;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseRegisterView;
use Carbon\Carbon;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Online\Entities\OnlineCourse;

class CalendarController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    public function index()
    {
        if (url_mobile()){
            return view('themes.mobile.frontend.calendar');
        }
        return view('frontend.calendar');
    }

    public function calendarWeek(Request $request)
    {
        $type = $request->type ? $request->type : 1;
        $list_course = $this->getAllCourse($type);

        $dt = now();
        if (isset($request->year) && isset($request->week)) {
            $dt->setISODate($request->year, $request->week);
        } else {
            $dt->setISODate($dt->format('o'), $dt->format('W'));
        }
        $year = $dt->format('o');
        $week = $dt->format('W');

        return view('frontend.calendar_week', [
            'list_course' => $list_course,
            'week' => $week,
            'dt' => $dt,
            'year' => $year,
        ]);
    }

    public function getData(Request $request)
    {
        $type = $request->type;
        $user_id = profile()->user_id;
        $result = [];
        $allCourse = $this->getAllCourse($type);

        foreach ($allCourse as $item){
            if ($item->course_type == 1) {
                $course = OnlineCourse::select(['color', 'i_text', 'b_text'])->where('id', $item->course_id)->first();
            }else{
                $course = OfflineCourse::select(['color', 'i_text', 'b_text'])->where('id', $item->course_id)->first();
            }

            $start_date = get_date($item->start_date, 'Y-m-d');
            $end_date = ($item->end_date ? Carbon::parse($item->end_date)->addDay(1)->format('Y-m-d') : '');

            if (url_mobile()) {
                $url = ($item->course_type == 1) ? route('themes.mobile.frontend.online.detail', ['course_id' => $item->course_id]) : route('themes.mobile.frontend.offline.detail', ['course_id' => $item->course_id]);
            }else{
                $check_register = CourseRegisterView::where('course_id', $item->course_id)
                ->where('course_type', $item->course_type)
                ->where('status', 1)
                ->where('user_id', $user_id)
                ->exists();
                if($check_register) {
                    $url = ($item->course_type == 1) ? route('module.online.detail_new', [$item->course_id]) : route('module.offline.detail_new', [$item->course_id]);
                } else {
                    $url = route('frontend.all_course', ['type' => $item->course_type, 'course_id' => $item->course_id]) ;
                }
            }

            $result[] = [
                'title' => $item->name,
                'start' => $start_date,
                'end' => $end_date,
                'url' => $url,
                'description' => $item->name . ' (' . $item->code .')'. PHP_EOL . get_date($start_date) . ($end_date ? ' - '. get_date($end_date) : ''),
                'backgroundColor' => ($course && !is_null($course->color)) ? $course->color : '',
                'className' => [
                    ($course && $course->i_text == 1) ? 'i_text' : '', 
                    ($course && $course->b_text == 1) ? 'b_text' : '',
                ],
            ];

        }

        return response()->json($result);
    }

    public function getAllCourse($type)
    {
        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->select([
            'el_course_view.course_id',
            'el_course_view.course_type',
            'el_course_view.code',
            'el_course_view.name',
            'el_course_view.start_date',
            'el_course_view.end_date'
        ]);
        if ($type == 1){ //Lịch của tôi. Những khoá có ghi danh và được duyệt
            $query->leftJoin('el_course_register_view as b', function ($join){
                $join->on('el_course_view.course_id','=','b.course_id');
                $join->on('el_course_view.course_type','=','b.course_type');
            });
            $query->where('b.user_id', '=', profile()->user_id);
            $query->where('b.status', 1);
        } else if ($type == 2) { //Lịch khoá online
            $query->where('el_course_view.course_type',1);
        } else { //Lịch khoá offline
            $query->where('el_course_view.course_type',2);
        }
        $query->where('el_course_view.status', '=', 1)
            ->where('el_course_view.isopen', '=', 1)
            ->where('el_course_view.offline', '=', 0);

        return $query->get();
    }
}
