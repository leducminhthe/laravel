<?php

namespace App\Http\Controllers\Mobile;

use App\Models\CourseView;
use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseRegisterView;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('themes.mobile.frontend.calendar');
    }

    public function getData(Request $request)
    {
        $type = $request->type;
        $user_id = profile()->user_id;
        $result = [];
        $allCourse = $this->getAllCourse($type);

        foreach ($allCourse as $item){
            $url = ($item->course_type == 1) ? route('themes.mobile.frontend.online.detail', ['course_id' => $item->course_id]) : route('themes.mobile.frontend.offline.detail', ['course_id' => $item->course_id]);

            $result[] = [
                'title' => $item->name,
                'start' => get_date($item->start_date, 'Y-m-d'),
                'end' => ($item->end_date ? Carbon::parse($item->end_date)->addDay(1)->format('Y-m-d') : ''),
                'url' => $url,
                'description' => $item->name . ' (' . $item->code .')'. PHP_EOL . get_date($item->start_date) . ($item->end_date ? ' - '. get_date($item->end_date) : ''),
            ];

        }

        return response()->json($result);
    }

    public function getAllCourse($type)
    {
        CourseView::addGlobalScope(new CompanyScope());
        $query = CourseView::query();
        $query->select(['el_course_view.*']);
        if ($type == 1){
            $query->leftJoin('el_course_register_view as b', function ($join){
                $join->on('el_course_view.course_id','=','b.course_id');
                $join->on('el_course_view.course_type','=','b.course_type');
            });
            $query->where('b.user_id', '=', profile()->user_id);
        } else if ($type == 2) {
            $query->where('el_course_view.course_type',1);
        } else {
            $query->where('el_course_view.course_type',2);
        }
        $query->where('el_course_view.status', '=', 1)
            ->where('el_course_view.offline','=',0)
            ->where('el_course_view.isopen', '=', 1);
        // dd($query->get());
        return $query->get();
    }
}
