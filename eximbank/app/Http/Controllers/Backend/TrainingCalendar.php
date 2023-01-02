<?php

namespace App\Http\Controllers\Backend;

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

class TrainingCalendar extends Controller
{
    public function index() {
        return view('backend.training_calendar.index');
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

            $result[] = [
                'title' => $item->name,
                'start' => $start_date,
                'end' => $end_date,
                'url' => '',
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
        $query->select(['el_course_view.*']);
        if ($type == 2) {
            $query->where('el_course_view.course_type', 1);
        } else if ($type == 3) {
            $query->where('el_course_view.course_type', 2);
        }
        $query->where('el_course_view.status', '=', 1)
            ->where('el_course_view.isopen', '=', 1)
            ->where('el_course_view.offline', '=', 0);
        return $query->get();
    }
}
