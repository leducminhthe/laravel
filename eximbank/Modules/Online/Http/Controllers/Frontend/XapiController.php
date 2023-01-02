<?php

namespace Modules\Online\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Online\Entities\ActivityScormAttemptData;
use Modules\Online\Entities\ActivityScormScore;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityXapi;
use Modules\Online\Entities\OnlineCourseLesson;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;

class XapiController extends Controller
{
    public function index($course_id, $activity_id,$lesson) {
        $course = OnlineCourse::findOrFail($course_id);
        $activity_scorm = OnlineCourseActivityScorm::findOrFail($activity_id);

        //$get_lesson_activities = OnlineCourseActivity::where('lesson_id',$lesson)->get();
        $item = OnlineCourse::find($course_id);
        $lessons_course = OnlineCourseLesson::where('course_id',$course_id)->get();

        $part = function ($subject_id){
            $user_type = Quiz::getUserType();
            $item = QuizPart::where('quiz_id', '=', $subject_id)
                ->whereIn('id', function ($subquery) use ($user_type, $subject_id) {
                    $subquery->select(['a.part_id'])
                        ->from('el_quiz_register AS a')
                        ->join('el_quiz_part AS b', 'b.id', '=', 'a.part_id')
                        ->where('a.quiz_id', '=', $subject_id)
                        ->where('a.user_id', '=', getUserId())
                        ->where('a.type', '=', $user_type)
                        ->where(function ($where){
                            $where->orWhere('b.end_date', '>', date('Y-m-d H:i:s'));
                            $where->orWhereNull('b.end_date');
                        });
                })->first();
            return $item;
        };

        if (session()->get('layout')){
            return view('themes.mobile.frontend.online_course.scorm.index', [
                'course' => $course,
                'activity' => $activity_scorm,
                'title' => $activity_scorm->course_activity->name,
            ]);
        }
        return view('online::scorm.index', [
            'course' => $course,
            'activity' => $activity_scorm,
            'title' => $activity_scorm->course_activity->name,
            'course_id' => $course_id,
            'item' => $item,
            //'get_lesson_activities' => $get_lesson_activities,
            'part' => $part,
            'lesson_course' => $lesson,
            'lessons_course' => $lessons_course
        ]);
    }

    public function getDataAttempt($activity_id, Request $request) {
        $activity = OnlineCourseActivityScorm::findOrFail($activity_id);

        //$search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = $activity->attempts()
            ->where('user_id', '=', profile()->user_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $score_scorm = ActivityScormScore::query()
                ->where('user_id', '=', getUserId())
                ->where('user_type', '=', getUserType())
                ->where('activity_id', '=', $activity_id)
                ->where('attempt_id', '=', $row->id)
                ->first();

            if ($score_scorm){
                $row->end_date = get_date($score_scorm->created_at, 'H:i:s d/m/Y');
                if (!is_null($score_scorm->score)) {
                    $row->grade = number_format($score_scorm->score, 2);
                }
                else {
                    $row->grade = null;
                }
            }

            $row->start_date = get_date($row->created_at, 'H:i:s d/m/Y');

        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function player($course_id, $activity_id, $attempt_id) {
        $course = OnlineCourse::findOrFail($course_id);
        $activity = OnlineCourseActivityXapi::findOrFail($activity_id);
        $attempt = $activity->attempts()
            ->where('id', $attempt_id)
            ->firstOrFail(['id','uuid']);

        return view('online::xapi.player', [
            'course' => $course,
            'activity' => $activity,
            'attempt' => $attempt,
            'user' => Auth::user(),
            'title' => $activity->course_activity->name,
        ]);
    }

    public function redirect(Request $request) {
        $scoid = $request->get('scoid');
        $uuid = $request->get('uuid');
        $activity = OnlineCourseActivityXapi::findOrFail($scoid);
        /**
         * Check gói xapi đã unzip thành công chưa?
         * */
        $xapi = $activity->xapi;
        if (empty($xapi->unzip_path)) {
            if ($xapi->error) {
                \Log::error($xapi->error);
            }

            return view('online::xapi.message', [
                'message' => 'Gói Tin can (XAPI) chưa sẵn sàng. Vui lòng quay lại sau!',
            ]);
        }

        /**
         * Get url xapi để play
         * */
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $xapi_url = $storage->url($xapi->unzip_path) . '/' . $xapi->index_file;
        $app = rtrim(config('app.url'),'/');
        /**
         * Nếu giao diện mobile => Thay đổi url datafile là url mobile để cho phép embed
         * */
        if (session()->get('layout')){
            $xapi_url = str_replace(config('app.url'), config('app.mobile_url'), $xapi_url);
            $app = rtrim(config('app.mobile_url'),'/');
        }
        $lrsUser = config('app.lrs.user');
        $lrsPass = config('app.lrs.pass');
        $lrsEndpoint = config('app.lrs.end_point');
        $queryString = http_build_query([
                'endpoint' => $lrsEndpoint,
                'auth' =>  'Basic '.base64_encode("{$lrsUser}:{$lrsPass}"),
                'actor' => '{"name":"'.Auth::user()->username.'","objectType":"Agent","mbox":"mailto:'.Auth::user()->email.'"}',
                'registration'=>$uuid
            ]);
        $xapi_url.='?'.urldecode($queryString);
        return redirect()->to($xapi_url);
    }

    public function checkNet(Request $request) {
        $scoid = $request->input('scoid');
        $attempt_id = $request->input('attempt');
        $activity = OnlineCourseActivityScorm::findOrFail($scoid);
        $attempt = $activity->attempts()->where('id', '=', $attempt_id)
            ->firstOrFail(['id']);

        $user_activity = $activity->users()->firstOrNew([
            'user_id' => getUserId(),
            'user_type' => getUserType(),
            'attempt_id' => $attempt->id,
        ]);

        $user_activity->touch();
        $user_activity->save();

        return response()->json([
            'status' => true,
        ]);
    }

    public function save(Request $request) {
        $scoid = $request->post('scoid');
        $attempt_id = $request->post('attempt');
        $varname = $request->post('varname');
        $varvalue = $request->post('varvalue');

        $activity = OnlineCourseActivityScorm::findOrFail($scoid);
        $attempt = $activity->attempts()
            ->where('id', '=', $attempt_id)
            ->firstOrFail(['id', 'lesson_location']);

        /**
         * Update lesson_location và suspend_data
         * lesson_location là slider người dùng đã học đến
         * suspend_data là dữ liệu ghi nhớ người học đã học đến slider nào?
         * Khi có suspend_data sẽ cho phép người dùng chuyển hướng đến phần bài học trước đó
         * */
        if ($varname == 'cmi.core.lesson_location') {
            $attempt->update([
                'lesson_location' => $varvalue,
            ]);
        }

        if ($varname == 'cmi.suspend_data') {
            $attempt->update([
                'suspend_data' => $varvalue,
            ]);
        }

        /**
         * Update data score
         * */
        $data = [];

        if ($varname == 'cmi.core.score.raw' || $varname == 'cmi.score.raw') {
            $data['score_raw'] = $varvalue;
        }

        if ($varname == 'cmi.core.score.max' || $varname == 'cmi.score.max') {
            $data['score_max'] = $varvalue;
        }

        if ($varname == 'cmi.core.score.min' || $varname == 'cmi.score.min') {
            $data['score_min'] = $varvalue;
        }

        if ($varname == 'cmi.core.lesson_status') {
            $data['status'] = $varvalue;
        }

        if ($varname == 'cmi.completion_status' && $varname == 'cmi.success_status'){
            $data['status'] = $varvalue. ', ' .$varvalue;
        }

        $user = $activity->scores()->firstOrNew([
            'user_id' => getUserId(),
            'user_type' => getUserType(),
            'attempt_id' => $attempt->id,
        ]);

        if ($user && $varname == 'cmi.core.score.raw') {
            /*
             * If set score_max in attempt user
             * */
            if ($user->score_max > 0) {
                /*
                * If setup scorm max_score and score.raw > 0
                * */
                if ($varvalue > 0 && $activity->max_score > 0) {
                    $per_score = $activity->max_score / $user->score_max;
                    $data['score'] = round($varvalue * $per_score, 2);
                }

                /**
                 * Mặc định khi gói scorm chưa trả ra điểm (cmi.core.score.raw)
                 * */

                /*else {

                    if ($attempt->activity_scorm->score_required || $attempt->activity_scorm->min_score_required) {
                        $data['score'] = $varvalue;
                    }
                }*/
            }
        }

        $user->fill($data);
        $user->save();

        $activity->scores()
            ->whereUserId(getUserId())
            ->where('user_type','=', getUserType())
            ->whereAttemptId($attempt->id)
            ->whereNull('score')
        ->update([
            'score' => $activity->max_score
        ]);
        /**
         * Save scorm data
         * $varname tên giá trị nhận được
         * $varvalue giá trị tương ứng của $varname
         * */
        ActivityScormAttemptData::updateOrCreate([
            'attempt_id' => $attempt_id,
            'var_name' => $varname,
        ], [
            'var_value' => $varvalue
        ]);

        return response()->json([
            'status' => true,
        ]);
    }
}
