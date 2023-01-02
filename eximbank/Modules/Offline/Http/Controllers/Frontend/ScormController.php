<?php

namespace Modules\Offline\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Offline\Entities\OfflineActivityScormAttemptData;
use Modules\Offline\Entities\OfflineActivityScormScore;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseActivity;
use Modules\Offline\Entities\OfflineCourseActivityCompletion;
use Modules\Offline\Entities\OfflineCourseActivityHistory;
use Modules\Offline\Entities\OfflineCourseActivityScorm;
use Modules\Offline\Entities\OfflineCourseActivityXapi;
use Modules\Offline\Entities\OfflineScorm;

class ScormController extends Controller
{
    public function index($course_id, $activity_id, $activity_type, $lesson) {
        $course = OfflineCourse::findOrFail($course_id);
        $activity_scorm = OfflineCourseActivityScorm::findOrFail($activity_id);

        $title = $activity_scorm->course_activity->where('course_id', $course_id)->where('activity_id', 1)->first()->name;

        return view('offline::scorm.index', [
            'course' => $course,
            'activity' => $activity_scorm,
            'title' => $title,
            'course_id' => $course_id,
            'lesson_course' => $lesson,
            'activity_type' => $activity_type
        ]);
    }

    public function getDataAttempt($activity_id, Request $request) {
        $activity = OfflineCourseActivityScorm::findOrFail($activity_id);

        $user_id = $request->user_id ? $request->user_id : getUserId();
        $user_type = $request->user_type ? $request->user_type : getUserType();
        //$search = $request->input('search');
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $query = $activity->attempts()
            ->where('user_id', '=', $user_id);

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row){
            $getTimeStart = OfflineCourseActivityHistory::where(['activity_id' => $activity_id, 'user_id' => profile()->user_id])->first();
            $row->timeStartLearn = get_date($getTimeStart->created_at, 'H:i:s d/m/Y');

            $getTimeEnd = OfflineCourseActivityCompletion::where(['activity_id' => $getTimeStart->course_activity_id, 'user_id' => profile()->user_id])->first();
            $row->timeEndLearn = isset($getTimeEnd) ? get_date($getTimeEnd->created_at, 'H:i:s d/m/Y') : '';

            $score_scorm = OfflineActivityScormScore::query()
                ->where('user_id', '=', $user_id)
                ->where('user_type', '=', $user_type)
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

    public function playScorm($course_id, $activity_id) {
        OfflineCourse::findOrFail($course_id);
        $user_id = getUserId();
        $user_type = getUserType();

        $activity = OfflineCourseActivityScorm::findOrFail($activity_id);

        switch ($activity->new_attempt_required) {
            case 1:
                /*
                 * Nếu chọn khi có kết quả.
                 * */

                $attempt = $activity->attempts()
                    ->where('user_id', '=', $user_id)
                    ->where('user_type', '=', $user_type)
                    ->orderBy('attempt', 'DESC')
                    ->first(['id']);

                if (empty($attempt)) {
                    $attempt = $activity->attempts()
                        ->create([
                            'user_id' => $user_id,
                            'user_type' => $user_type,
                            'attempt' => 1,
                        ]);
                }
                else {
                    $score_exists = $activity->scores()
                        ->where('user_id', '=', $user_id)
                        ->where('user_type', '=', $user_type)
                        ->where('attempt_id', '=', $attempt->id)
                        ->where('score', '>', 0)
                        ->exists();

                    if ($score_exists) {
                        $count_attempt = $activity->attempts()
                            ->where('user_id', '=', $user_id)
                            ->where('user_type', '=', $user_type)
                            ->count('id');

                        $attempt = $activity->attempts()
                            ->create([
                                'user_id' => $user_id,
                                'user_type' => $user_type,
                                'attempt' => $count_attempt + 1,
                            ]);
                    }
                }

                break;
            case 2:
                /*
                * Nếu luôn luôn tạo lần thử mới
                * */
                $count_attempt = $activity->attempts()
                    ->where('user_id', '=', $user_id)
                    ->where('user_type', '=', $user_type)
                    ->count('id');

                if ($activity->max_attempt > 0) {
                    if ($count_attempt > $activity->max_attempt) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Bạn đã hết lần học bài học này.',
                        ]);
                    }
                }

                $attempt = $activity->attempts()
                    ->create([
                        'user_id' => $user_id,
                        'user_type' => $user_type,
                        'attempt' => $count_attempt + 1,
                    ]);

                break;
            default:
                /**
                 * Nếu chọn không => Luôn vào lần thử đầu tiên
                 * */
                $attempt = $activity->attempts()
                    ->where('user_id', '=', $user_id)
                    ->where('user_type', '=', $user_type)
                    ->first(['id']);

                if (empty($attempt)) {
                    $attempt = $activity->attempts()
                        ->create([
                            'user_id' => $user_id,
                            'user_type' => $user_type,
                            'attempt' => 1,
                        ]);
                }

                break;
        }

        return response()->json([
            'status' => 'success',
            'redirect' => route('module.offline.scorm.player', [
                $course_id,
                $activity_id,
                $attempt->id,
            ]),
        ]);
    }
    private function playXapi($course_id,$activity_id){
        OfflineCourse::findOrFail($course_id);
        $user_id = getUserId();
        $user_type = getUserType();

        $activity = OfflineCourseActivityXapi::findOrFail($activity_id);

        switch ($activity->new_attempt_required) {
            case 1:
                /*
                 * Nếu chọn khi có kết quả.
                 * */

                $attempt = $activity->attempts()
                    ->where('user_id', '=', $user_id)
                    ->where('user_type', '=', $user_type)
                    ->orderBy('attempt', 'DESC')
                    ->first(['id','uuid']);

                if (empty($attempt)) {
                    $attempt = $activity->attempts()
                        ->create([
                            'course_id' => $course_id,
                            'user_id' => $user_id,
                            'user_type' => $user_type,
                            'attempt' => 1,
                            'uuid' => (string) Str::uuid(),
                        ]);
                }
                else {
                    $score_exists = $activity->scores()
                        ->where('user_id', '=', $user_id)
                        ->where('user_type', '=', $user_type)
                        ->where('attempt_id', '=', $attempt->id)
                        ->where('score', '>', 0)
                        ->exists();

                    if ($score_exists) {
                        $count_attempt = $activity->attempts()
                            ->where('user_id', '=', $user_id)
                            ->where('user_type', '=', $user_type)
                            ->count('id');

                        $attempt = $activity->attempts()
                            ->create([
                                'course_id' => $course_id,
                                'user_id' => $user_id,
                                'user_type' => $user_type,
                                'attempt' => $count_attempt + 1,
                                'uuid' => (string) Str::uuid(),
                            ]);
                    }
                }

                break;
            case 2:
                /*
                * Nếu luôn luôn tạo lần thử mới
                * */
                $count_attempt = $activity->attempts()
                    ->where('user_id', '=', $user_id)
                    ->where('user_type', '=', $user_type)
                    ->count('id');

                if ($activity->max_attempt > 0) {
                    if ($count_attempt > $activity->max_attempt) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Bạn đã hết lần học bài học này.',
                        ]);
                        // return '<div class="out_of_scorm"><h3>Bạn đã hết lần học bài học này.</h3></div>';
                    }
                }

                $attempt = $activity->attempts()
                    ->create([
                        'course_id' => $course_id,
                        'user_id' => $user_id,
                        'user_type' => $user_type,
                        'attempt' => $count_attempt + 1,
                        'uuid' => (string) Str::uuid(),
                    ]);

                break;
            default:
                /**
                 * Nếu chọn không => Luôn vào lần thử đầu tiên
                 * */
                $attempt = $activity->attempts()
                    ->where('user_id', '=', $user_id)
                    ->where('user_type', '=', $user_type)
                    ->first(['id','uuid']);

                if (empty($attempt)) {
                    $attempt = $activity->attempts()
                        ->create([
                            'course_id' => $course_id,
                            'user_id' => $user_id,
                            'user_type' => $user_type,
                            'attempt' => 1,
                            'uuid' => (string) Str::uuid(),
                        ]);
                }

                break;
        }

        return \Response::json([
            'status' => 'success',
            'redirect' => route('module.offline.xapi.player', [
                $course_id,
                $activity_id,
                $attempt->id,
            ])
        ]);
    }

    public function play($course_id, $activity_id, $activity_type) {
        if ($activity_type==1)
            return $this->playScorm($course_id,$activity_id);
        elseif ($activity_type==5)
            return $this->playXapi($course_id, $activity_id);
    }

    public function player($course_id, $activity_id, $attempt_id) {
        $course = OfflineCourse::findOrFail($course_id);
        $activity = OfflineCourseActivityScorm::findOrFail($activity_id);
        $attempt = $activity->attempts()
            ->where('id', $attempt_id)
            ->firstOrFail(['id', 'suspend_data']);
        $title = $activity->course_activity->where('course_id', $course_id)->where('activity_id', 1)->first()->name;
        return view('offline::scorm.player', [
            'course' => $course,
            'activity' => $activity,
            'attempt' => $attempt,
            'user' => Auth::user(),
            'title' => $title,
        ]);
    }

    public function redirect(Request $request) {
        $scoid = $request->get('scoid');
        $activity = OfflineCourseActivityScorm::findOrFail($scoid);

        /**
         * Check gói scorm đã unzip thành công chưa?
         * */
        $scorm = OfflineScorm::whereOriginPath($activity->path)->first();
        if (empty($scorm->unzip_path)) {

            $message = 'Gói Scorm chưa sẵn sàng. Vui lòng quay lại sau!';

            if ($scorm->error) {
                \Log::error($scorm->error);

                $message = $scorm->error;
            }

            return view('offline::scorm.message', [
                'message' => $message,
            ]);
        }

        /**
         * Get url scorm để play
         * */
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $scorm_url = $storage->url($scorm->unzip_path) . '/' . $scorm->index_file;

        /**
         * Nếu giao diện mobile => Thay đổi url datafile là url mobile để cho phép embed
         * */
        if (url_mobile()){
            $scorm_url = str_replace(config('app.url'), config('app.mobile_url'), $scorm_url);
        }

        return redirect()->to($scorm_url);
    }

    public function checkNet(Request $request) {
        $scoid = $request->input('scoid');
        $attempt_id = $request->input('attempt');
        $activity = OfflineCourseActivityScorm::findOrFail($scoid);
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

        $activity = OfflineCourseActivityScorm::findOrFail($scoid);
        $attempt = $activity->attempts()
            ->where('id', '=', $attempt_id)
            ->firstOrFail(['id', 'lesson_location', 'attempt']);

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
        $user_id = getUserId();
        $user_type = getUserType();
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
        //* update activity complete **/
        $course_activity = OfflineCourseActivity::where(['course_id'=>$activity->course_id,'activity_id'=>1, 'subject_id'=>$scoid])->first();
        if($activity->type_result==1){ // nhận kết quả từ scorm
            if ($data['status'] ) {
                $result =  strpos($data['status'],'completed')>=0 || strpos($data['status'],'passed')>=0;
                $this->updateOfflineCourseCompleteFromScorm($activity, $scoid, $user_id, $user_type, $result, $attempt->attempt);
            }
        }
        else {
            if ($activity->score_required == 0) {
                $this->updateOfflineCourseCompleteScorm($activity, $scoid, $user_id, $user_type, $attempt->attempt);

            } elseif ($data['status']) {
                $this->updateOfflineCourseCompleteScorm($activity, $scoid, $user_id, $user_type, $attempt->attempt);
            }
        }
        $activity->scores()
            ->whereUserId(getUserId())
            ->where('user_type','=', getUserType())
            ->whereAttemptId($attempt->id)
            ->whereNull('score')
            ->where(function ($sub){
                $sub->orWhere('status', 'like', '%completed%');
                $sub->orWhere('status', 'like', '%passed%');
            })->update([
                'score' => $activity->max_score
            ]);
        /**
         * Save scorm data
         * $varname tên giá trị nhận được
         * $varvalue giá trị tương ứng của $varname
         * */
        OfflineActivityScormAttemptData::updateOrCreate([
            'attempt_id' => $attempt_id,
            'var_name' => $varname,
        ], [
            'attempt_id' => $attempt_id,
            'var_name' => $varname,
            'var_value' => $varvalue
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function updateOfflineCourseCompleteFromScorm(OfflineCourseActivityScorm $activity,$scoid,$user_id,$user_type,$result, $num_attempt)
    {
        $course_activity = OfflineCourseActivity::where(['course_id'=>$activity->course_id,'activity_id'=>1, 'subject_id'=>$scoid])->first();
        $completion = OfflineCourseActivityCompletion::firstOrNew([
            'user_id' => $user_id,
            'course_id'=>$activity->course_id,
            'activity_id' => $course_activity->id,
        ]);
        $completion->user_id = $user_id;
        $completion->user_type = $user_type;
        $completion->activity_id = $course_activity->id;
        $completion->course_id = $activity->course_id;
        $completion->status = $result;
        $completion->save();
    }
    public function updateOfflineCourseCompleteScorm(OfflineCourseActivityScorm $activity,$scoid,$user_id,$user_type, $num_attempt)
    {
        $course_activity = OfflineCourseActivity::where(['course_id'=>$activity->course_id,'activity_id'=>1, 'subject_id'=>$scoid])->first();
        $result = $course_activity->checkComplete($user_id, $user_type);
        $completion = OfflineCourseActivityCompletion::firstOrNew([
            'user_id' => $user_id,
            'course_id'=>$activity->course_id,
            'activity_id' => $course_activity->id,
        ]);
        $completion->user_id = $user_id;
        $completion->user_type = $user_type;
        $completion->activity_id = $course_activity->id;
        $completion->course_id = $activity->course_id;
        $completion->status = $result?1:0;
        $completion->save();
    }
}
