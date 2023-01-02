<?php

namespace Modules\Quiz\Http\Controllers\Mobile;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizAttemptsAgain;
use Modules\Quiz\Entities\QuizGraded;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizUserReview;

class QuizController extends Controller
{
    public function index($quiz_id, $part_id)
    {
        $user_id = profile()->user_id;
        $quiz = Quiz::whereId($quiz_id)
            ->whereStatus(1)
            ->whereIsOpen(1)
            ->firstOrFail();
        $part = QuizPart::query()
            ->where('quiz_id', $quiz_id)
            ->where('id', '=', $part_id)
            ->whereExists(function ($subquery) use ($user_id, $quiz) {
                $subquery->select(['a.id'])
                    ->from('el_quiz_register AS a')
                    ->where('a.quiz_id', '=', $quiz->id)
                    ->where('a.user_id', '=', $user_id)
                    ->whereColumn('a.part_id', '=', 'el_quiz_part.id');
            })->firstOrFail();
        $count_quiz_attempts = QuizAttempts::where('quiz_id',$quiz_id)->where('part_id',$part_id)->where('user_id',$user_id)->count();
        $can_create = $this->canCreateQuiz($quiz, $part, $count_quiz_attempts);

        $quiz_register = QuizRegister::whereQuizId($quiz_id)->where('part_id', $part_id)->where('user_id', $user_id)->first();
        $quiz_user_review = QuizUserReview::where('quiz_id',$quiz->id)->where('part_id',$part_id)->where('user_id',$user_id)->first();

        $user_locked = $quiz_register->locked ?? 0;
        $block_quiz = $quiz_register->block_quiz ?? 0;

        return view('themes.mobile.frontend.quiz.goquiz',[
            'quiz' => $quiz,
            'part' => $part,
            'can_create' => $can_create,
            'user_locked' => $user_locked,
            'block_quiz' => $block_quiz,
        ]);
    }

    public function indexByOnline($quiz_id, $part_id)
    {
        $user_id = profile()->user_id;
        $quiz = Quiz::whereId($quiz_id)->whereStatus(1)->whereIsOpen(1)->firstOrFail();
        $part = QuizPart::query()
            ->where('quiz_id', $quiz_id)
            ->where('id', '=', $part_id)
            ->whereExists(function ($subquery) use ($user_id, $quiz) {
                $subquery->select(['a.id'])
                    ->from('el_quiz_register AS a')
                    ->where('a.quiz_id', '=', $quiz->id)
                    ->where('a.user_id', '=', $user_id)
                    ->whereColumn('a.part_id', '=', 'el_quiz_part.id');
            })->firstOrFail();
        $count_quiz_attempts = QuizAttempts::where('quiz_id',$quiz->id)->where('part_id',$part_id)->where('user_id',$user_id)->count();
        $can_create = $this->canCreateQuiz($quiz, $part, $count_quiz_attempts);
        $quiz_register = QuizRegister::whereQuizId($quiz_id)->where('part_id', $part_id)->where('user_id', $user_id)->first();

        $user_locked = $quiz_register->locked ?? 0;
        $block_quiz = $quiz_register->block_quiz ?? 0;

        return view('themes.mobile.frontend.quiz.goquiz_by_online',[
            'quiz' => $quiz,
            'part' => $part,
            'can_create' => $can_create,
            'user_locked' => $user_locked,
            'block_quiz' => $block_quiz,
        ]);
    }

    /**
     * Create Quiz attempt for user
     * @param int $quiz_id
     * @param int $part_id
     * @return \Illuminate\Http\JsonResponse
     * */
    public function createQuiz($quiz_id, $part_id, Request $request) {
        $quiz_by_online = $request->quiz_by_online;
        $user_type = getUserType();

        $quiz = Quiz::findOrFail($quiz_id);
        $user_id = profile()->user_id;
        $part = QuizPart::where(['id'=>$part_id])->select('id', 'start_date','end_date')->firstOrFail();
        $count_attempt = QuizAttempts::countQuizAttempt($quiz_id,$user_id);
        if (!$this->canCreateQuiz($quiz, $part,$count_attempt)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn không được làm thêm bài thi này hoặc số lần thử đã hết',
            ]);
        }
        $quiz_setting = null;
        $attempt = QuizAttempts::where('quiz_id', '=', $quiz_id)
            ->where('part_id', '=', $part_id)
            ->where('user_id', '=', $user_id)
            ->select('id','timestart','timefinish')
            ->orderBy('id', 'DESC')
            ->first();
        if ($attempt) {
            $end_time = $attempt->timestart + ($quiz->limit_time * 60);
            if ($end_time < time()) { // kỳ thi đã trải qua
                $attempt = null;
            }elseif ($attempt->timefinish>0){
                $attempt = null;
                $quiz_setting = QuizSetting::whereQuizId($quiz_id)->first();
            }
        }
        if (empty($attempt)) {
            $time_start = time();
            $num_attempt = $count_attempt + 1;
            $attempt = new QuizAttempts();
            $attempt->createTemplate($time_start,$num_attempt,$user_id,$part_id,$quiz_id);
        }

        $result = QuizResult::firstOrNew(['quiz_id'=> $quiz_id,'user_id'=>$user_id, 'part_id' => $part_id]);
        $result->quiz_id = $quiz_id;
        $result->part_id = $part_id;
        $result->user_id = $user_id;
        $result->type = $user_type;
        $result->save();

        return redirect()->action([DoQuizController::class,'index'],['quiz_id' => $quiz_id,'part_id' => $part_id,'attempt_id' => $attempt->id, 'quiz_by_online' => $quiz_by_online])
            ->with(['attempt'=>$attempt,'quiz'=>$quiz,'quiz_setting'=>$quiz_setting]);
    }

    public function canCreateQuiz(Quiz $quiz, $part, $count_attempt)
    {
        $count_attempt_again = QuizAttemptsAgain::where('quiz_id',$quiz->id)->where('part_id',$part->id)->where('user_id', profile()->user_id)->sum('attempt');

        $storage = \Storage::disk('local');
        $template = 'quiz/' . $quiz->id . '/template/1.json';
        if (!$storage->exists($template)) {
            return false;
        }
        if ($part->end_date && strtotime($part->end_date) < time())
            return false;
        if (time()< strtotime($part->start_date))
            return false;
//        if ($user_attempt <= $quiz->max_attempts || $quiz->max_attempts == 0 || (($attempt->timestart + ($quiz->limit_time * 60)) > time() && $attempt->timefinish == 0)) {
        if (($count_attempt - $count_attempt_again) < (int)$quiz->max_attempts || $quiz->max_attempts == 0) {
            return true;
        }

        return false;
    }

    public function attemptHistory($quiz_id, $part_id) {
        //$attempts = $this->dataAttemptHistory($quiz_id, $part_id);

        return view('themes.mobile.frontend.quiz.attempt_history',[
            'quiz_id' => $quiz_id,
            'part_id' => $part_id,
            //'attempts' => $attempts
        ]);
    }

    public function dataAttemptHistory($quiz_id, $part_id) {
        $user_id = profile()->user_id;
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();
        $max_end_date = $quiz->end_quiz;
        $quiz_setting = QuizSetting::where('quiz_id', '=', $quiz_id)->first();
        $graded = QuizGraded::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->first();
        $query = QuizAttempts::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('part_id', '=', $part_id);
        $query->where('user_id', '=', $user_id);
        $query->orderBy('id', 'desc');
        $rows = $query->get();
        $check_essay = 0;
        foreach ($rows as $row) {
            $status = '';
            switch ($row->state) {
                case 'inprogress': $status = 'Đang làm bài'; break;
                case 'completed': $status = 'Đã nộp bài'; break;
            }

            $row->start_date = date('H:i d/m/Y', $row->timestart);
            $row->end_date = $row->timefinish > 0 ? date('H:i d/m/Y', $row->timefinish) : '';

            if ($check_essay == 1 && !$graded){
                $row->grade = '_';
            }else {
                $row->grade = $quiz_setting ? (($quiz_setting->after_test_score == 1 || ($quiz_setting->exam_closed_score == 1 && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d'))) ? round($row->sumgrades, 2) : '_') : '_';
            }

            $row->status = $status;

            $row->after_review = ($quiz_setting == null)? 0 : $quiz_setting->after_test_review_test;

            $row->closed_review = $quiz_setting && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d') ? $quiz_setting->exam_closed_review_test : 0;

            if ($quiz_setting){
                $row->review_link = ($quiz_setting->after_test_review_test == 1 || $quiz_setting->exam_closed_review_test == 1) ? route('module.quiz_mobile.doquiz.do_quiz', ['quiz_id' => $quiz_id, 'part_id' => $row->part_id, 'attempt_id' => $row->id]): '';
            }
        }

        return $rows;
    }

    public function getDataAttemptHistory($quiz_id, $part_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_id = profile()->user_id;
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();
        $max_end_date = $quiz->end_quiz;
        $quiz_setting = QuizSetting::where('quiz_id', '=', $quiz_id)->first();
        $graded = QuizGraded::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->first();
        $query = QuizAttempts::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('part_id', '=', $part_id);
        $query->where('user_id', '=', $user_id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        $check_essay = 0;
        foreach ($rows as $row) {
            $status = '';
            switch ($row->state) {
                case 'inprogress': $status = 'Đang làm bài'; break;
                case 'completed': $status = 'Đã nộp bài'; break;
            }

            $row->start_date = date('H:i d/m/Y', $row->timestart);
            $row->end_date = $row->timefinish > 0 ? date('H:i d/m/Y', $row->timefinish) : '';

            if ($check_essay == 1 && !$graded){
                $row->grade = '_';
            }else {
                $row->grade = $quiz_setting ? (($quiz_setting->after_test_score == 1 || ($quiz_setting->exam_closed_score == 1 && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d'))) ? round($row->sumgrades, 2) : '_') : '_';
            }

            $row->status = $status;

            $row->after_review = ($quiz_setting == null)? 0 : $quiz_setting->after_test_review_test;

            $row->closed_review = $quiz_setting && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d') ? $quiz_setting->exam_closed_review_test : 0;

            if ($quiz_setting){
                $row->review_link = ($quiz_setting->after_test_review_test == 1 || $quiz_setting->exam_closed_review_test == 1) ? route('module.quiz_mobile.doquiz.do_quiz', ['quiz_id' => $quiz_id, 'part_id' => $row->part_id, 'attempt_id' => $row->id]): '';
            }
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    /**
     * Get question of quiz
     * @param int $quiz_id
     * @param int $part_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * */
    public function getAttemptHistory($quiz_id, $part_id, Request $request) {
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'desc');
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 20);

        $user_id = profile()->user_id;
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();
        $max_end_date = $quiz->end_quiz;
        $quiz_setting = QuizSetting::where('quiz_id', '=', $quiz_id)->first();
        $graded = QuizGraded::where('quiz_id', '=', $quiz_id)
            ->where('user_id', '=', $user_id)
            ->first();
        $query = QuizAttempts::query();
        $query->where('quiz_id', '=', $quiz_id);
        $query->where('part_id', '=', $part_id);
        $query->where('user_id', '=', $user_id);
        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        $check_essay = 0;
        foreach ($rows as $row) {
            $status = '';
            switch ($row->state) {
                case 'inprogress': $status = 'Đang làm bài'; break;
                case 'completed': $status = 'Hoàn thành'; break;
            }

            $row->start_date = date('H:i d/m/Y', $row->timestart);
            $row->end_date = $row->timefinish > 0 ? date('H:i d/m/Y', $row->timefinish) : '';

            if ($check_essay == 1 && !$graded){
                $row->grade = '_';
            }else {
                $row->grade = $quiz_setting ? (($quiz_setting->after_test_score == 1 || ($quiz_setting->exam_closed_score == 1 && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d'))) ? round($row->sumgrades, 2) : '_') : '_';
            }

            $row->status = $status;

            $row->after_review = ($quiz_setting == null)? 0 : $quiz_setting->after_test_review_test;

            $row->closed_review = $quiz_setting && date('H:i') > get_date($max_end_date, 'H:i') && date('Y-m-d') > get_date($max_end_date, 'Y-m-d') ? $quiz_setting->exam_closed_review_test : 0;

            if ($quiz_setting){
                $row->review_link = ($quiz_setting->after_test_review_test == 1 || $quiz_setting->exam_closed_review_test == 1) ? route('module.quiz_mobile.doquiz.do_quiz', ['quiz_id' => $quiz_id, 'part_id' => $row->part_id, 'attempt_id' => $row->id]): '';
            }
        }

        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function getUserType() {
        if (\Auth::check()) {
            return 1;
        }

        if (\Auth::guard('secondary')->check()) {
            return 2;
        }

        return null;
    }

    public function getUserId() {
        if (\Auth::check()) {
            return profile()->user_id;
        }

        if (\Auth::guard('secondary')->check()) {
            return \Auth::guard('secondary')->id();
        }

        return null;
    }

    public function userReviewQuiz($quiz_id, $part_id, Request $request){
        $user_id = profile()->user_id;
        $content = $request->input('content_review');
        $content = $request->input('content_review');

        $title_id = null;
        $title_name = null;
        $unit_id = null;
        $unit_name = null;
        $parent_unit_id = null;
        $parent_unit_name = null;

            $profile = Profile::query()
                ->select([
                    'code',
                    \DB::raw('CONCAT(lastname, \' \', firstname) as name'),
                    'email',
                ])->where('user_id', '=', $user_id)->first();

            $title = $profile->titles;
            $unit = $profile->unit;
            $parent_unit = @$unit->parent;

            $title_id = @$title->id;
            $title_name = @$title->name;
            $unit_id = @$unit->id;
            $unit_name = @$unit->name;
            $parent_unit_id = @$parent_unit->id;
            $parent_unit_name = @$parent_unit->name;

            $username = User::find($user_id)->username;

        $model = new QuizUserReview();
        $model->quiz_id = $quiz_id;
        $model->part_id = $part_id;
        $model->user_id = $user_id;
        $model->user_type = $user_type;
        $model->user_code = $profile->code;
        $model->full_name = $profile->name;
        $model->username = $username;
        $model->email = $profile->email;
        $model->title_id = $title_id;
        $model->title_name = $title_name;
        $model->unit_id = $unit_id;
        $model->unit_name = $unit_name;
        $model->parent_unit_id = $parent_unit_id;
        $model->parent_unit_name = $parent_unit_name;
        $model->content = $content;
        $model->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cảm ơn bạn đã góp ý.',
            'redirect' => route('module.quiz_mobile.doquiz.index', [
                'quiz_id' => $quiz_id,
                'part_id' => $part_id,
            ]),
        ]);
    }
}
