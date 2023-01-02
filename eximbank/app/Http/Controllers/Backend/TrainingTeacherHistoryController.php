<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\TrainingTeacher;
use App\Models\Categories\TrainingTeacherHistory;
use App\Models\Categories\TrainingTeacherStar;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\OfflineTeacher;
use Modules\Offline\Entities\OfflineTeacherClass;

class TrainingTeacherHistoryController extends Controller
{
    public function index($teacher_id) {
        $model = TrainingTeacher::findOrFail($teacher_id);
        $page_title = $model->name;

        return view('backend.category.training_teacher.history',[
            'model' => $model,
            'page_title' => $page_title,
        ]);
    }

    public function getDataHistory($teacher_id, Request $request) {
        $year = date('Y');
        $search = $request->input('search');
        $searchYear = $request->year;
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = OfflineTeacherClass::query();
        $query->select([
            'el_offline_teacher_class.*',
            'b.code',
            'b.name',
            'class.name as class_name',
        ]);
        $query->from('el_offline_teacher_class');
        $query->Join('el_offline_course as b', 'b.id', 'el_offline_teacher_class.course_id');
        $query->Join('offline_course_class as class', 'class.id', '=', 'el_offline_teacher_class.class_id');
        $query->where('el_offline_teacher_class.teacher_id', $teacher_id);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('b.name', 'like', '%'. $search .'%');
                $subquery->orWhere('b.code', 'like', '%'. $search .'%');
            });
        }

        if ($searchYear) {
            $query->whereYear('b.created_at', $searchYear);
        } else {
            $query->where(DB::raw('year('.DB::getTablePrefix().'el_offline_teacher_class.created_at)'), date('Y'));
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);

        $rows = $query->get();
        foreach ($rows as $row) {
            $row->course = $row->name .' ('. $row->code .')';

            //Tổng buổi 1 lớp của khoá học
            $row->schedule = TrainingTeacherHistory::where('teacher_id', $row->teacher_id)
            ->where('course_id', $row->course_id)
            ->where('class_id', $row->class_id)
            ->whereYear('created_at', date('Y'))
            ->sum('num_schedule');

            //Số lần làm GV chính
            $row->teacher_main = TrainingTeacherHistory::where('teacher_id', $row->teacher_id)
            ->where('course_id', $row->course_id)
            ->where('class_id', $row->class_id)
            ->whereYear('created_at', date('Y'))
            ->where('teacher_type', 1)
            ->count();

            //Số lần làm trợ giảng
            $row->tutors = TrainingTeacherHistory::where('teacher_id', $row->teacher_id)
            ->where('course_id', $row->course_id)
            ->where('class_id', $row->class_id)
            ->whereYear('created_at', date('Y'))
            ->where('teacher_type', 2)
            ->count();

            //Tổng số h giảng
            $row->num_hour = TrainingTeacherHistory::query()->disableCache()
            ->join('el_offline_schedule as schedule', 'schedule.id', '=', 'el_training_teacher_history.schedule_id')
            ->where('el_training_teacher_history.teacher_id', $row->teacher_id)
            ->where('el_training_teacher_history.course_id', $row->course_id)
            ->where('el_training_teacher_history.class_id', $row->class_id)
            ->whereYear('el_training_teacher_history.created_at', date('Y'))
            ->sum('el_training_teacher_history.num_hour');

            $row->num_student = OfflineRegister::where('course_id', $row->course_id)
            ->where('class_id', $row->class_id)->where('status', 1)->count();

            //Tổng chi phí giảng
            $cost = TrainingTeacherHistory::where('teacher_id', $row->teacher_id)
            ->where('course_id', $row->course_id)
            ->where('class_id', $row->class_id)
            ->whereYear('created_at', date('Y'))
            ->sum('cost');
            $row->cost = number_format($cost, 0);

            $num_user_rating = TrainingTeacherStar::where('teacher_id', $row->teacher_id)
                ->where('course_id', $row->course_id)
                ->where('course_type', 2)
                ->where('class_id', $row->class_id)
                ->count();
            $num_star = TrainingTeacherStar::where('teacher_id', $row->teacher_id)
                ->where('course_id', $row->course_id)
                ->where('course_type', 2)
                ->where('class_id', $row->class_id)
                ->sum('num_star');

            $row->tbc_num_star = (int)$num_star > 0 ? round($num_star/$num_user_rating, 1) : 0;
        }

        json_result(['total' => $count, 'rows' => $rows]);
    }
}
