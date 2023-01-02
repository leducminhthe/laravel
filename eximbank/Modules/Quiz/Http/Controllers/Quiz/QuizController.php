<?php

namespace Modules\Quiz\Http\Controllers\Quiz;

use App\Models\Categories\Titles;
use App\Models\Profile;
use App\Models\ProfileView;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizGraded;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizQuestion;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizTeacherGraded;
use Modules\Quiz\Entities\QuizUpdateAttempts;
use Modules\Quiz\Entities\QuizUserReview;
use Modules\Quiz\Entities\QuizUserSecondary;
use Modules\Quiz\Http\Helpers\AttemptTemplate;
use Modules\Quiz\Entities\QuizAttemptsAgain;

class QuizController extends BaseController
{
    public function index($quiz_id, $part_id)
    {
        $profile = profile();
        $user_id = $profile->user_id;
        $user_type = Quiz::getUserType();

        $quiz = Quiz::whereId($quiz_id)->whereStatus(1)->whereIsOpen(1)->firstOrFail();
        if($quiz->quiz_not_register == 1) {
            $save = QuizRegister::firstOrNew(['quiz_id' => $quiz_id, 'part_id' => $part_id, 'user_id' => $user_id]);
            $save->quiz_id = $quiz_id;
            $save->user_id = $user_id;
            $save->type = $user_type;
            $save->part_id = $part_id;
            $save->created_by = 2;
            $save->updated_by = 2;
            $save->unit_by = 1;
            $save->save();
        }

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
        $count_quiz_question = QuizQuestion::whereQuizId($quiz_id)->count();
        $quiz_register = QuizRegister::whereQuizId($quiz_id)->where('part_id', $part_id)->where('user_id', $user_id)->first();
        $quiz_user_review = QuizUserReview::where('quiz_id',$quiz->id)->where('part_id',$part_id)->where('user_id',$user_id)->first();

        $user_locked = $quiz_register->locked ?? 0;
        $block_quiz = $quiz_register->block_quiz ?? 0;

        return view('quiz::quiz.index', [
            'quiz' => $quiz,
            'part' => $part,
            'can_create' => $can_create,
            'profile' => $profile,
            'count_quiz_question' => $count_quiz_question,
            'count_quiz_attempts' => $count_quiz_attempts,
            'descriptions_quiz' => $quiz->description,
            'user_locked' => $user_locked,
            'block_quiz' => $block_quiz,
            'quiz_user_review' => $quiz_user_review,
        ]);
    }

    public function indexByOnline($quiz_id, $part_id)
    {
        $profile = profile();
        $user_id = $profile->user_id;

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
        $count_quiz_question = QuizQuestion::whereQuizId($quiz_id)->count();
        $quiz_register = QuizRegister::whereQuizId($quiz_id)->where('part_id', $part_id)->where('user_id', $user_id)->first();

        $user_locked = $quiz_register->locked ?? 0;
        $block_quiz = $quiz_register->block_quiz ?? 0;

        return view('quiz::quiz.index_by_online', [
            'quiz' => $quiz,
            'part' => $part,
            'can_create' => $can_create,
            'profile' => $profile,
            'count_quiz_question' => $count_quiz_question,
            'count_quiz_attempts' => $count_quiz_attempts,
            'descriptions_quiz' => $quiz->description,
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
        $text_quiz = $request->text_quiz ? $request->text_quiz : null;
        $quiz_by_online = $request->quiz_by_online;

        $user_type = getUserType();
        $quiz = Quiz::findOrFail($quiz_id);
        $user_id =$request->input('user_id')?$request->input('user_id'):profile()->user_id;
        $part = QuizPart::where(['id'=>$part_id])->select('id','start_date','end_date')->firstOrFail();
        $count_attempt = QuizAttempts::where('quiz_id',$quiz->id)->where('part_id',$part_id)->where('user_id',$user_id)->count();

        if (!$this->canCreateQuiz($quiz, $part, $count_attempt, $text_quiz)) {
            if($quiz_by_online){
                return redirect()->route('module.quiz.doquiz.index_by_online', [$quiz_id, $part_id]);
            }else{
                return redirect()->route('module.quiz.doquiz.index', [$quiz_id, $part_id]);
            }
            // return response()->json([
            //     'status' => 'error',
            //     'message' => '1. Bạn không được làm thêm bài thi này <br>  2. số lần thử đã hết <br> 3. Bộ đề thi chưa có',
            // ]);
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
            $attempt->createTemplate($time_start, $num_attempt, $user_id, $part_id, $quiz_id, $text_quiz);
        }

        if(empty($text_quiz)) {
            $result = QuizResult::firstOrNew(['quiz_id'=> $quiz_id,'user_id'=>$user_id, 'part_id' => $part_id]);
            $result->quiz_id = $quiz_id;
            $result->part_id = $part_id;
            $result->user_id = $user_id;
            $result->type = $user_type;
            $result->save();
        }

        return redirect()->action([DoQuizController::class,'index'],['quiz_id' => $quiz_id, 'part_id' => $part_id, 'attempt_id' => $attempt->id, 'quiz_by_online' => $quiz_by_online])
            ->with(['attempt' => $attempt, 'quiz' => $quiz, 'quiz_setting' => $quiz_setting]);
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

        $user_id = $request->user_id ? $request->user_id : profile()->user_id;
        $quiz = Quiz::where('id', '=', $quiz_id)->firstOrFail();
        $quiz_part = QuizPart::whereQuizId($quiz_id)->where('id', $part_id)->first();
        $max_end_date = $quiz_part->end_date;

        $quiz_setting = QuizSetting::where('quiz_id', '=', $quiz_id)->first();
        $graded = QuizTeacherGraded::where('quiz_id', '=', $quiz_id)
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
        foreach ($rows as $row) {
            $status = '';
            switch ($row->state) {
                case 'inprogress': $status = 'Đang làm bài'; break;
                case 'completed': $status = 'Đã nộp bài'; break;
            }
            $row->start_date = date('H:i d/m/Y', $row->timestart);
            $row->end_date = $row->timefinish > 0 ? date('H:i d/m/Y', $row->timefinish) : '';
            $row->timer = $row->timefinish > 0 ? calculate_time_span(($row->timefinish), ($row->timestart)) : '';

            if ($row->teacher_grade == 1 && !$graded){
                $row->grade = '_';
            }else{
                $row->grade = $quiz_setting ? (($quiz_setting->after_test_score == 1 || ($quiz_setting->exam_closed_score == 1 && date('H:i') >= get_date($max_end_date, 'H:i') && date('Y-m-d') >= get_date($max_end_date, 'Y-m-d'))) ? round($row->sumgrades, 2) : '_') : '_';
            }
            $row->status = $status;
            $row->after_review = ($quiz_setting == null)? 0 : $quiz_setting->after_test_review_test;
            $row->closed_review = $quiz_setting && date('H:i') >= get_date($max_end_date, 'H:i') && date('Y-m-d') >= get_date($max_end_date, 'Y-m-d') ? $quiz_setting->exam_closed_review_test : 0;

            $row->review_link = route('module.quiz.doquiz.do_quiz', ['quiz_id' => $quiz_id, 'part_id' => $row->part_id, 'attempt_id' => $row->id]);
        }
        return response()->json(['total' => $count, 'rows' => $rows]);
    }

    public function canCreateQuiz(Quiz $quiz, $part, $count_attempt, $text_quiz = null)
    {
        //Chưa có đề thi
        $storage = \Storage::disk('local');
        $template = 'quiz/' . $quiz->id . '/template/1.json';
        if (!$storage->exists($template)) {
            return false;
        }

        if($text_quiz == 1) {
            return true;
        } else {
            $count_attempt_again = QuizAttemptsAgain::where('quiz_id',$quiz->id)->where('part_id',$part->id)->where('user_id', profile()->user_id)->sum('attempt');

            //Đã kết thúc kỳ thi
            if ($part->end_date && strtotime($part->end_date) < time()){
                return false;
            }

            //Chưa tới thời gian thi
            if (time() < strtotime($part->start_date)){
                return false;
            }

            //Còn số lần làm bài
            if (($count_attempt - $count_attempt_again) < (int)$quiz->max_attempts || $quiz->max_attempts == 0) {
                return true;
            }
            return false;
        }
    }

    public function userReviewQuiz($quiz_id, $part_id, Request $request){
        $user_id = profile()->user_id;
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
            'redirect' => route('module.quiz.doquiz.index', [
                'quiz_id' => $quiz_id,
                'part_id' => $part_id,
            ]),
        ]);
    }
}
