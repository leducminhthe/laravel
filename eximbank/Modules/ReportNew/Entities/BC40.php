<?php

namespace Modules\ReportNew\Entities;

use App\Models\CacheModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\CourseRegisterView;
use App\Models\CourseView;
use Modules\Quiz\Entities\QuizResult;

class BC40 extends Model
{
    public static function sql($year, $course_type, $user_id, $title_id, $unit_id)
    {
        $query = CourseRegisterView::query();
        $query->select([
            'register.user_id',
            'course.name',
            'course.code',
            'course.start_date',
            'course.end_date',
            'course.course_type',
            'course.course_id as id',
            'profile.full_name',
            'profile.title_name',
            'profile.unit_name',
        ])->disableCache();
        $query->from("el_course_register_view AS register");
        $query->join('el_course_view as course',function ($join){
            $join->on('course.course_id', '=', 'register.course_id');
            $join->on('course.course_type', '=', 'register.course_type');
        });
        $query->join('el_profile_view as profile', 'profile.user_id', '=', 'register.user_id');
        $query->where(['course.offline' => 0]);
        $query->where('register.user_id', '>', 2);
        $query->whereYear('course.created_at', $year);
        $query->orderBy('register.user_id');

        if (isset($course_type)) {
            $query->where('course.course_type', $course_type);
        }

        if (isset($user_id)) {
            $users = explode(',', $user_id);
            $query->whereIn('register.user_id', $users);
        }

        if (isset($title_id)) {
            $titles = explode(',', $title_id);
            $query->whereIn('profile.title_id', $titles);
        }

        if (isset($unit_id)) {
            $query->where('profile.unit_id', $unit_id);
        }
        $course = $query;

        $query = QuizResult::query();
        $query->select([
            'result.user_id',
            'quiz.name',
            'quiz.code',
            'quiz.start_quiz as start_date',
            'quiz.end_quiz as end_date',
            \DB::raw('3 as course_type'),
            'quiz.id as id',
            'profile.full_name',
            'profile.title_name',
            'profile.unit_name',
        ])->disableCache();
        $query->from('el_quiz_result as result');
        $query->join('el_quiz AS quiz', 'quiz.id', '=', 'result.quiz_id');
        $query->join('el_profile_view AS profile', 'profile.user_id', '=', 'result.user_id');
        $query->where('quiz.quiz_type', 3);
        $query->where('result.timecompleted', '>', 0);
        $query->whereNull('result.text_quiz');
        $query->whereYear('quiz.created_at', $year);
        $query->where('result.user_id', '>', 2);
        $query->orderBy('result.user_id');

        if (isset($course_type) && $course_type == 3) {
            $query->where('quiz.quiz_type', 3);
        }

        if (isset($user_id)) {
            $users = explode(',', $user_id);
            $query->whereIn('result.user_id', $users);
        }

        if (isset($title_id)) {
            $titles = explode(',', $title_id);
            $query->whereIn('profile.title_id', $titles);
        }

        if (isset($unit_id)) {
            $query->where('profile.unit_id', $unit_id);
        }

        $query->union($course);
        $query_sql = $query->toSql();
        $query = \DB::table(\DB::raw("($query_sql) AS q"))->mergeBindings($query->getQuery());
        $query->orderBy('user_id');
        return $query;
    }
}
