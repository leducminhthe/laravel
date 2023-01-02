<?php

namespace Modules\ReportNew\Http\Controllers;

use App\Models\CourseRegisterView;
use App\Models\CourseView;
use App\Models\Categories\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\ReportNew\Entities\BC40;
use function GuzzleHttp\json_decode;
use Carbon\Carbon;
use Modules\Capabilities\Entities\CapabilitiesReview;
use Modules\Capabilities\Entities\CapabilitiesTitle;
use Modules\Offline\Entities\OfflineAttendance;
use Modules\Offline\Entities\OfflineSchedule;
use Modules\Offline\Entities\OfflineTeamsAttendanceReport;
use Modules\Online\Entities\OnlineCourseTimeUserLearn;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\ReportNew\Entities\BC41;

class BC41Controller extends ReportNewController
{
    public function review(Request $request, $key)
    {
        $report = parent::reportList();
        $reportGroupList = $this->reportGroupList();
        return view('reportnew::review', [
            'report' => strtolower($key),
            'name'=> $report[strtoupper($key)],
            'reportList' => $reportGroupList,
        ]);
    }

    public function getData(Request $request)
    {
        $show = $request->show;
        $title_id = $request->title_id;

        if ($show == 0)
            json_result([]);

        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = BC41::sql($title_id);
        $count = $query->count();
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $user_arr = $row->profiles->pluck('user_id')->toArray();

            $total = count($user_arr);
            $num_rating = CapabilitiesReview::whereIn('user_id', $user_arr)->count();

            $row->title_name = $row->name;
            $row->total = $total;

            $row->percent = CapabilitiesTitle::where('title_id', $row->id)->sum('weight') .' %';
            $row->score = number_format(CapabilitiesTitle::where('title_id', $row->id)->sum('goal'), 2);

            $row->num_not_rating = ($total - $num_rating);
            $row->{'1_30'} = rand(1, $total);
            $row->{'30_50'} = rand(1, $total);
            $row->{'50_60'} = rand(1, $total);
            $row->{'60_70'} = rand(1, $total);
            $row->{'70_80'} = rand(1, $total);
            $row->{'80_90'} = rand(1, $total);
            $row->{'90_100'} = rand(1, $total);
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
