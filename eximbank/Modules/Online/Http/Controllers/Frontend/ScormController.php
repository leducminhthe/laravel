<?php

namespace Modules\Online\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Online\Entities\ActivityScormAttemptData;
use Modules\Online\Entities\ActivityScormScore;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\Online\Entities\OnlineCourseActivityScorm;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityXapi;
use Modules\Online\Entities\OnlineCourseLesson;
use Modules\Online\Entities\OnlineCourseSettingPercent;
use Modules\Online\Entities\Scorm;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizPart;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;

class ScormController extends Controller
{
    public function index($course_id, $activity_id, $activity_type, $lesson) {
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
        $title = $activity_scorm->course_activity->where('course_id', $course_id)->where('activity_id', 1)->first()->name;
        if (url_mobile()){
            return view('themes.mobile.frontend.online_course.scorm.index', [
                'course' => $course,
                'activity' => $activity_scorm,
                'title' => $title,
            ]);
        }
        return view('online::scorm.index', [
            'course' => $course,
            'activity' => $activity_scorm,
            'title' => $title,
            'course_id' => $course_id,
            'item' => $item,
            //'get_lesson_activities' => $get_lesson_activities,
            'part' => $part,
            'lesson_course' => $lesson,
            'lessons_course' => $lessons_course,
            'activity_type' => $activity_type
        ]);
    }

    public function getDataAttempt($activity_id, Request $request) {
        $activity = OnlineCourseActivityScorm::findOrFail($activity_id);

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
            $getTimeStart = OnlineCourseActivityHistory::where(['activity_id' => $activity_id, 'user_id' => profile()->user_id])->first();
            $row->timeStartLearn = get_date($getTimeStart->created_at, 'H:i:s d/m/Y');

            $getTimeEnd = OnlineCourseActivityCompletion::where(['activity_id' => $getTimeStart->course_activity_id, 'user_id' => profile()->user_id])->first();
            $row->timeEndLearn = isset($getTimeEnd) ? get_date($getTimeEnd->created_at, 'H:i:s d/m/Y') : '';

            $score_scorm = ActivityScormScore::query()
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

    public function playScorm($course_id, $activity_id, $preview = 0) {
        OnlineCourse::findOrFail($course_id);
        $user_id = getUserId();
        $user_type = getUserType();

        $activity = OnlineCourseActivityScorm::findOrFail($activity_id);

        if($preview != 1){
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
        }

        if (url_mobile()){
            $title = $activity->course_activity->where('course_id', $course_id)->where('activity_id', 1)->first()->name;
            return response()->json([
                'status' => 'success',
                'redirect' =>  route('themes.mobile.frontend.online.view_scorm', [
                    $course_id,
                    $activity_id,
                    $attempt->id,
                    'title' => $title,
                ])
            ]);
        }

        $attemp_id = ($preview != 1 ? $attempt->id : 0);
        return response()->json([
            'status' => 'success',
            'redirect' => route('module.online.scorm.player', [
                $course_id,
                $activity_id,
                $attemp_id,
                'preview' => $preview,
            ]),
        ]);
    }
    private function playXapi($course_id,$activity_id, $preview = 0){
        OnlineCourse::findOrFail($course_id);
        $user_id = getUserId();
        $user_type = getUserType();

        $activity = OnlineCourseActivityXapi::findOrFail($activity_id);

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

        if (session()->get('layout')){
            return response()->json([
                'status' => 'success',
                'redirect' =>  route('themes.mobile.frontend.online.view_xapi', [
                    $course_id,
                    $activity_id,
                    $attempt->id,
                    'title' => $activity->course_activity->name,
                ])
            ]);
        }

        return \Response::json([
            'status' => 'success',
            'redirect' => route('module.online.xapi.player', [
                $course_id,
                $activity_id,
                $attempt->id,
                'preview' => $preview,
            ])
        ]);
    }

    public function play($course_id, $activity_id, $activity_type, Request $request) {
        $preview = $request->preview;
        if ($activity_type==1)
            return $this->playScorm($course_id,$activity_id, $preview);
        elseif ($activity_type==7)
            return $this->playXapi($course_id, $activity_id, $preview);
    }

    public function player($course_id, $activity_id, $attempt_id, Request $request) {
        $preview = $request->preview;

        $course = OnlineCourse::findOrFail($course_id);
        $activity = OnlineCourseActivityScorm::findOrFail($activity_id);

        if($preview != 1){
            $attempt = $activity->attempts()
            ->where('id', $attempt_id)
            ->firstOrFail(['id', 'suspend_data']);
        }else{
            $attempt = null;
        }

        $get_title = $activity->course_activity->where('course_id', $course_id)->where('activity_id', 1)->first();
        $title = isset($get_title) ? $get_title->name : '';
        return view('online::scorm.player', [
            'course' => $course,
            'activity' => $activity,
            'attempt' => $attempt,
            'user' => Auth::user(),
            'title' => $title,
            'preview' => $preview,
        ]);
    }

    public function redirect(Request $request) {
        $scoid = $request->get('scoid');
        $activity = OnlineCourseActivityScorm::findOrFail($scoid);

        /**
         * Check gói scorm đã unzip thành công chưa?
         * */
        $scorm = Scorm::whereOriginPath($activity->path)->first();
        if (empty($scorm->unzip_path)) {

            $message = 'Gói Scorm chưa sẵn sàng. Vui lòng quay lại sau!';

            if ($scorm->error) {
                \Log::error($scorm->error);

                $message = $scorm->error;
            }

            return view('online::scorm.message', [
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
        $activity = OnlineCourseActivityScorm::findOrFail($scoid);

        if($attempt_id > 0){
            $attempt = $activity->attempts()->where('id', '=', $attempt_id)
            ->firstOrFail(['id']);

            $user_activity = $activity->users()->firstOrNew([
                'user_id' => getUserId(),
                'user_type' => getUserType(),
                'attempt_id' => $attempt->id,
            ]);

            $user_activity->touch();
            $user_activity->save();
        }


        return response()->json([
            'status' => true,
        ]);
    }

    public function save(Request $request) {
        $preview = $request->preview;

        if($preview != 1){
            $scoid = $request->post('scoid');
            $attempt_id = $request->post('attempt');
            $varname = $request->post('varname');
            $varvalue = $request->post('varvalue');

            $activity = OnlineCourseActivityScorm::findOrFail($scoid);
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
            $course_activity = OnlineCourseActivity::where(['course_id'=>$activity->course_id,'activity_id'=>1, 'subject_id'=>$scoid])->first();
            if($activity->type_result==1){ // nhận kết quả từ scorm
                if ($data['status'] ) {
                    $result =  strpos($data['status'],'completed')>=0 || strpos($data['status'],'passed')>=0;
                    $this->updateOnlineCourseCompleteFromScorm($activity, $scoid, $user_id, $user_type, $result, $attempt->attempt);
                    if ($data['score'])
                        OnlineCourseSettingPercent::updateOrInsert([
                            'course_id' => $activity->course_id,
                            'course_activity_id' => $course_activity->id,
                        ], [
                            'course_id' => $activity->course_id,
                            'course_activity_id' => $course_activity->id,
                            'score' => isset($data['score']) ? (int)$data['score'] : 0,
                        ]);
                }
            }
            else {
                if ($activity->score_required == 0) {
                    $this->updateOnlineCourseCompleteScorm($activity, $scoid, $user_id, $user_type, $attempt->attempt);

                } elseif ($data['status']) {
                    $this->updateOnlineCourseCompleteScorm($activity, $scoid, $user_id, $user_type, $attempt->attempt);
                    if ($data['score'])
                        OnlineCourseSettingPercent::updateOrInsert([
                            'course_id' => $activity->course_id,
                            'course_activity_id' => $course_activity->id,
                        ], [
                            'course_id' => $activity->course_id,
                            'course_activity_id' => $course_activity->id,
                            'score' => isset($data['score']) ? (int)$data['score'] : 0,
                        ]);
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
            ActivityScormAttemptData::updateOrCreate([
                'attempt_id' => $attempt_id,
                'var_name' => $varname,
            ], [
                'attempt_id' => $attempt_id,
                'var_name' => $varname,
                'var_value' => $varvalue
            ]);
        }

        return response()->json([
            'status' => true,
        ]);
    }

    public function updateOnlineCourseCompleteFromScorm(OnlineCourseActivityScorm $activity,$scoid,$user_id,$user_type,$result, $num_attempt)
    {
        $course_activity = OnlineCourseActivity::where(['course_id'=>$activity->course_id,'activity_id'=>1, 'subject_id'=>$scoid])->first();
        $completion = OnlineCourseActivityCompletion::firstOrNew([
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

        if($completion->status == 1){
            $this->pointUserByAttempt($activity, $user_id, $course_activity, $num_attempt);

            \Artisan::call('online:complete '.$user_id .' '.$activity->course_id);
        }
    }
    public function updateOnlineCourseCompleteScorm(OnlineCourseActivityScorm $activity,$scoid,$user_id,$user_type, $num_attempt)
    {
        $course_activity = OnlineCourseActivity::where(['course_id'=>$activity->course_id,'activity_id'=>1, 'subject_id'=>$scoid])->first();
        $result = $course_activity->checkComplete($user_id, $user_type);
        $completion = OnlineCourseActivityCompletion::firstOrNew([
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

        if($completion->status == 1){
            $this->pointUserByAttempt($activity, $user_id, $course_activity, $num_attempt);

            \Artisan::call('online:complete '.$user_id .' '.$activity->course_id);
        }
    }

    public function pointUserByAttempt(OnlineCourseActivityScorm $activity, $user_id, OnlineCourseActivity $course_activity, $num_attempt){
        $check = UserPointResult::query()
            ->leftJoin('el_userpoint_settings', 'el_userpoint_settings.id', '=', 'el_userpoint_result.setting_id')
            ->where('el_userpoint_settings.item_id', '=', $activity->course_id)
            ->where('el_userpoint_settings.item_type', '=', 2)
            ->where('el_userpoint_settings.pkey','=', 'online_activity_complete')
            ->where('el_userpoint_result.user_id', '=', $user_id)
            ->where('el_userpoint_result.ref', '=', $course_activity->id);
        if(!$check->exists()){
            $user_point_setting = UserPointSettings::whereItemId($activity->course_id)
                ->where('item_type', '=', 2)
                ->where('pkey','=', 'online_activity_complete')
                ->where('note', '=', 'attempt')
                ->where('ref', '=', $course_activity->id)
                ->get();
            if ($user_point_setting->count() > 0) {
                $course = OnlineCourse::find($activity->course_id);
                foreach($user_point_setting as $item){
                    if($num_attempt >= $item->min_score && $num_attempt <= $item->max_score){
                        $note = 'Hoàn thành hoạt động <b>'. $course_activity->name .'</b> của khóa học online <b>'. $course->name .' ('. $course->code .')</b>';

                        $exists = UserPointResult::where("setting_id","=",$item->id)->where("user_id","=",$user_id)->whereNull("type")->first();
                        if(!$exists){
                            UserPointResult::create([
                                'setting_id' => $item->id,
                                'user_id' => $user_id,
                                'content' => $note,
                                'point' => $item->pvalue,
                                'ref' => $course_activity->id,
                                'type_promotion' => 0,
                            ]);

                            $user_point = PromotionUserPoint::firstOrNew(['user_id' => $user_id]);
                            $user_point->point = $user_point->point + $item->pvalue;
                            $user_point->level_id = PromotionLevel::levelUp($user_point->point, $user_id);
                            $user_point->save();
                        }
                    }
                }
            }
        }
    }
}
