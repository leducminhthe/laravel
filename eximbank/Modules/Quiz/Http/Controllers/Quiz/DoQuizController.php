<?php

namespace Modules\Quiz\Http\Controllers\Quiz;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuestionAnswer;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizAttempts;
use Modules\Quiz\Entities\QuizCameraImage;
use Modules\Quiz\Entities\QuizPart;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;
use Modules\Quiz\Entities\QuizSetting;
use Modules\Quiz\Entities\QuizUserError;
use Modules\Quiz\Entities\QuizAttemptHistory;
use Modules\Quiz\Http\Helpers\AttemptGrade;
use Modules\Quiz\Http\Helpers\AttemptTemplate;
use Modules\Quiz\Entities\QuizUpdateAttempts;

class DoQuizController extends BaseController
{
    public function index($quiz_id, $part_id, $attempt_id, Request $request) {
        $quiz_by_online = $request->quiz_by_online;
        $part = QuizPart::find($part_id);
        $attempt = QuizAttempts::find($attempt_id);
        $quiz = session('quiz');
        if (!$quiz)
            $quiz = Quiz::find($quiz_id);
        $quiz_setting = session('quiz_setting');
        if (!$quiz_setting)
            $quiz_setting = QuizSetting::whereQuizId($quiz_id)->first();
        /*******************************************/
        $template = QuizAttempts::getQuizData($attempt_id);
        $questions = $template['questions'];
        usort($questions, function ($a, $b) {
            return $a['qindex'] <=> $b['qindex'];
        });
        $qqcategorys = $template['categories'];
        $qqcategory = [];
        if ($qqcategorys){
            foreach ($qqcategorys as $item) {
                $qqcategory['num_' . $item['num_order']] = $item['name'];
                $qqcategory['percent_' . $item['num_order']] = $item['percent_group'];
            }
        }

        $text_quiz = 0;
        if($attempt->text_quiz == 1) {
            $user_lock = 1;
            $quiz_register = '';
            $text_quiz = 1;
        } else {
            $quiz_register = QuizRegister::whereQuizId($quiz_id)->where('part_id', $part_id)->where('user_id', profile()->user_id)->first();
            $user_lock = session(['user_lock_'.$quiz_register->id => $quiz_register->locked]);
        }

        return view('quiz::quiz.doquiz', [
            'quiz' => $quiz,
            'part' => $part,
            'part_id' => $part_id,
            'attempt' => $attempt,
            'questions' => $questions,
            'attempt_finish' => $attempt->timefinish?1:0,
            'qqcategory' => $qqcategory,
            'quiz_setting' => $quiz_setting,
            'max_end_date' => $quiz->end_quiz,
            'quiz_by_online' => $quiz_by_online,
            'quiz_register' => $quiz_register,
            'user_lock' => $user_lock,
            'text_quiz' => $text_quiz
        ]);
    }

    public function getQuestionQuiz($quiz_id, $part_id, $attempt_id, Request $request) {
        $quiz = Quiz::findOrFail($quiz_id);
        $template = QuizAttempts::getQuizData($attempt_id);
        $total = count( $template['questions'] );
        $total_page = ceil( $total / $quiz->questions_perpage );
        $page = $request->get('page');
        $offset = ($page - 1) * $quiz->questions_perpage;
        if( $offset < 0 ) $offset = 0;
        $rows = array_slice( $template['questions'], $offset, $quiz->questions_perpage );
        $next = false;
        if ($page < $total_page)
            $next = true;
        return response()->json([
            'rows' => $rows,
            'next' => $next
        ]);
    }

    public function saveUserQuiz($quiz_id, $part_id, $attempt_id, Request $request) {

        $template = QuizAttempts::getQuizData($attempt_id);
        $quiz = $template['quiz'];
        $attempt = $template['attempt'];
        if (QuizAttempts::isAttemptFinish($attempt['timestart'],$attempt['timefinish'],$quiz['limit_time'])) {
            return response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save')
            ]);
        }
        $qids = (array) $request->input('q', []);
        $questions = $template['questions'];
        foreach ($qids as $index => $question_id){
            $question = $questions[$question_id];

            $anwsers = $request->{'q_' . $question['id']};
            $matching = $request->{'matching_' . $question['id']};
            $score = 0;

            if ($question['type'] == 'essay'){
                if ($anwsers) {
                    $question['text_essay'] = $anwsers;
                    $question['selected'] = true;
                }
            }

            if ($question['type'] == 'matching'){
                if ($matching) {
                    $question['matching'] = $matching;
                    $question['selected'] = true;
                    /**********************/
                    $matching_select = $matching;
                    $correct_answers = [];
                    $answers = QuestionAnswer::where('question_id', '=', $question['question_id'])->get();
                    $count = 0;
                    foreach ($answers as $answer){
                        if ($matching_select[$answer->id] == $answer->matching_answer){
                            $count += 1;
                            $correct_answers[] = $answer->id;
                        }
                    }
                    if ($count == $answers->count()){
                        $score = ($question['score_group'] * $question['max_score']);
                    }

                    $question['correct_answers'] = $correct_answers;
                }
            }

            if ($question['type'] == 'multiple-choise') {
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /*************************/
                    if ($question['multiple'] == 0){
                        $true_answer = count(array_intersect($anwsers,$question['correct_answers']));
                        $score = ($question['score_group'] * $question['max_score']) * $true_answer;
                    }
                    elseif ($question['multiple'] == 1){
                        $answer_selected = $anwsers;
                        $answersOrg = $question['answers'];
                        $answersCorrect = $question['correct_answers'];

                        if (count($answer_selected) == count($answersOrg) && count($answersCorrect) < count($answersOrg)){ // Số câu tl đúng nhỏ hơn câu tl setup mà HV chọn hết
                            $score = 0;
                        }elseif (count($answer_selected) > count($answersCorrect) && count($answersCorrect) < count($answersOrg)){ // Số câu tl đúng nhỏ hơn câu tl setup mà HV chọn hơn sl câu đúng
                            $score = 0;
                        }else{
                            $score = 0;
                            if($question['multiple_full_score'] == 1){
                                $total_correct = count(array_intersect($answer_selected, $answersCorrect));
                                if($total_correct == count($answersCorrect)){
                                    $score += ($question['score_group'] * $question['max_score']);
                                }
                            }else{
                                foreach ($answersOrg as $item){
                                    if(in_array($item['id'],$answer_selected)){
                                        $score += (($question['score_group'] * $question['max_score']) * $item['percent_answer'])/100;
                                    }
                                }
                            }
                        }


                    }
                }
            }

            if ($question['type'] == 'fill_in'){
                if ($anwsers) {
                    $question['text_essay'] = $anwsers;
                    $question['selected'] = true;
                }
            }

            if ($question['type'] == 'fill_in_correct'){
                if ($anwsers) {
                    $question['text_essay'] = $anwsers;
                    $question['selected'] = true;
                    /****************/
                    $fill_in_correct_selected = $question['text_essay'];
                    $answersOrg = $question['answers'];
                    $count = 0;
                    $percent = ($answersOrg->count() > 0) ? 100/$answersOrg->count() : 0;
                    foreach ($answersOrg as $key => $answer){
                        if (\Str::lower($answer->fill_in_correct_answer) == \Str::lower($fill_in_correct_selected[$key])){
                            $count += $percent;
                        }
                    }
                    $score = (($question['score_group'] * $question['max_score']) * $count) / 100;
                }
                else{
                    $score = 0;
                }
            }

            if ($question['type'] == 'select_word_correct') {
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /******************/
                    $answer_selected = $question['answer'];
                    $true_answer = count(array_intersect($anwsers,$question['correct_answers']));
                    $totalAnswer = count($answer_selected);
                    $score_answer_true_avg = $totalAnswer>0 ? $true_answer/$totalAnswer : 0;
                    $score = ($question['score_group'] * $question['max_score']) * $score_answer_true_avg;
                }
            }

            if($question['type'] == 'drag_drop_marker'){
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /*************************/
                    $answer_selected = $anwsers;
                    $answersOrg = $question['answers'];

                    $count_total = 0; //đếm tổng đáp án có tọa độ
                    $count_correct = 0; //đếm tổng đáp án đúng
                    foreach ($answersOrg as $item){
                        $id = $item['id'];
                        if($item['marker_answer']){
                            $count_total += 1;

                            if($answer_selected[$id] == $item['marker_answer']){
                                $count_correct += 1;
                            }
                        }
                    }
                    $score_answer_true_avg = $count_total>0 ? $count_correct/$count_total : 0;
                    $score = ($question['score_group'] * $question['max_score']) * $score_answer_true_avg;
                }
            }

            if ($question['type'] == 'drag_drop_image') {
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /******************/
                    $true_answer = count(array_intersect($anwsers, $question['correct_answers']));
                    $totalAnswer = count($question['correct_answers']);
                    $score_answer_true_avg = $totalAnswer>0 ? $true_answer/$totalAnswer : 0;
                    $score = ($question['score_group'] * $question['max_score']) * $score_answer_true_avg;
                }
            }

            if ($question['type'] == 'drag_drop_document') {
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /******************/
                    $true_answer = count(array_intersect($anwsers, $question['correct_answers']));
                    $totalAnswer = count($question['correct_answers']);
                    $score_answer_true_avg = $totalAnswer>0 ? $true_answer/$totalAnswer : 0;
                    $score = ($question['score_group'] * $question['max_score']) * $score_answer_true_avg;
                }
            }

            $question['score'] = $score;
            $questions[$question_id] = $question;
        }
        $template['questions'] = $questions;

        QuizAttempts::updateQuizData($attempt_id,$template);

        return response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }

    public function saveFileQuestionEssay($quiz_id, $part_id, $attempt_id, Request $request){
        $attempt = QuizAttempts::findOrFail($attempt_id);

        $question_id = $request->input('question_id');
        $file = $request->file('file_path');

        $filename = $question_id . '-' . $attempt_id .'.' . $file->getClientOriginalExtension();
        $file_path = 'quiz/' . $quiz_id . '/files';

        $storage = \Storage::disk('local');
        if (!$storage->exists($file_path)) {
            \File::makeDirectory($storage->path($file_path), 0777, true);
        }
        $storage->putFileAs($file_path, $file, $filename);

        $template = QuizAttempts::getQuizData($attempt_id);
        $template['questions'][$question_id]['file_essay'] = $filename;
        $template['questions'][$question_id]['link_file_essay'] = \link_download($file_path.'/' . $filename);
        QuizAttempts::updateQuizData($attempt_id,$template);

        return response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }

    public function submitQuiz($quiz_id, $part_id, $attempt_id, Request $request) {
        $quiz_attempt = QuizAttempts::find($attempt_id);
        $quiz_by_online = $request->quiz_by_online;
        $template = QuizAttempts::getQuizData($attempt_id);
        if ($template['attempt']['timefinish'] > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn đã nộp bài thi này không thể nộp lại',
            ]);
        }
        $complete = QuizAttempts::quizComplete($attempt_id, $quiz_id, $template['quiz'], $template['attempt']);

        $template['attempt']['state'] = 'completed';
        $template['attempt']['timefinish'] = time();

        QuizAttempts::updateQuizData($attempt_id,$template);

        if($quiz_attempt->text_quiz == 1) {
            $score = $complete;
            $redirect = route('module.quiz.edit', [
                'id' => $quiz_id,
            ]);
            QuizResult::where(['quiz_id'=> $quiz_id, 'user_id'=> profile()->user_id, 'part_id' => $part_id, 'text_quiz' => 1])->delete();
            QuizAttempts::find($attempt_id)->delete();
            QuizAttemptHistory::where('attempt_id', $attempt_id)->delete();
            QuizUpdateAttempts::where(['quiz_id'=> $quiz_id, 'user_id'=> profile()->user_id, 'part_id' => $part_id, 'attempt_id' => $attempt_id])->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Bạn đã đạt được số điểm là: '. $score,
                'text_quiz' => 1,
                'redirect' => $redirect,
            ]);
        } else {
            if($quiz_by_online){
                $redirect = route('module.quiz.doquiz.index_by_online', [
                    'quiz_id' => $quiz_id,
                    'part_id' => $part_id,
                ]);
            }else{
                $redirect = route('module.quiz.doquiz.index', [
                    'quiz_id' => $quiz_id,
                    'part_id' => $part_id,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã nộp bài thi thành công',
            'redirect' => $redirect,
        ]);
    }

    /**
     * Save quiz user attempt
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws
     * */
    public function saveImage($quiz_id, $part_id, $attempt_id, Request $request) {
        $image = $request->input('image');
        $image = str_replace('data:image/png;base64,', '', $image);

        $file_name = $attempt_id . '-' . $this->getUserId() . '-' . $this->getUserType() .'-' . \Str::random(10) . '.png';
        $file_path = 'quiz/'. $quiz_id . '/camera';

        $storage = \Storage::disk('local');
        if (!$storage->exists($file_path)) {
            \File::makeDirectory($storage->path($file_path), 0777, true);
        }
        $storage->put($file_path.'/'.$file_name, base64_decode($image));

        QuizCameraImage::create([
            'user_id' => $this->getUserId(),
            'user_type' => $this->getUserType(),
            'attempt_id' => $attempt_id,
            'path' => $file_path.'/'.$file_name
        ]);

        return response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }

    /**
     * Save error user attempt
     * @param int $quiz_id
     * @param int $part_id
     * @param int $attempt_id
     * @return \Illuminate\Http\JsonResponse
     * @throws
     * */
    public function saveErrorUser($quiz_id, $part_id, $attempt_id) {
        $user_type = $this->getUserType();
        $user_id = profile()->user_id;

        $count_attempt = QuizUserError::whereQuizId($quiz_id)
            ->whereAttemptId($attempt_id)
            ->wherePartId($part_id)
            ->whereUserId($user_id)
            ->count();

        $error = new QuizUserError();
        $error->attempt_id = $attempt_id;
        $error->quiz_id = $quiz_id;
        $error->part_id = $part_id;
        $error->user_id = $user_id;
        $error->attempt = $count_attempt + 1;
        $error->note = 'Trả lời sai câu hỏi';
        $error->save();

        return json_result([
            'attempt' => $error->attempt,
            'message' => (3 - $error->attempt) == 0 ? 'Bạn hết lượt trả lời' : 'Bạn còn ' . (3 - $error->attempt) . ' lần trả lời',
            'status' => 'error',
        ]);
    }

    public function checkUserQuestion($quiz_id, $part_id, $attempt_id, Request $request){
        $user_id = $this->getUserId();
        $key = $request->key;
        $answer = $request->answer;

        if (!$answer){
            return json_message('Chưa nhập câu hỏi đầy đủ','error');
        }

        $profile = Profile::where('user_id', '=', $user_id)->where('status', '=', 1);
        switch ($key){
            case 'month' :
                $profile->where(\DB::raw('month(dob)'), '=', $answer);
                break;
            case 'day' :
                $profile->where(\DB::raw('day(dob)'), '=', $answer);
                break;
            case 'year' :
                $profile->where(\DB::raw('year(dob)'), '=', $answer);
                break;
            case 'join_company' :
                $profile->where('join_company', '=', date_convert($answer));
                break;
            case 'code' :
                $profile->where('code', '=', $answer);
                break;
            case 'phone' :
                $profile->where('phone', '=', $answer);
                break;
            case 'identity_card' :
                $profile->where('identity_card', '=', $answer);
                break;
            case 'unit_code' :
                $profile->where('unit_code', '=', $answer);
                break;
            case 'title_code' :
                $profile->where('title_code', '=', $answer);
                break;
        }

        $profile = $profile->first();
        if ($profile){
            return json_result([
                'user_id' => $profile ? $profile->user_id : ''
            ]);
        }else{
            return json_message('Thông tin không chính xác','error');
        }
    }

    public function saveUserFlag($quiz_id, $part_id, $attempt_id, Request $request) {
        $template = QuizAttempts::getQuizData($attempt_id);
        $quiz = $template['quiz'];
        $attempt = $template['attempt'];
        if (QuizAttempts::isAttemptFinish($attempt['timestart'],$attempt['timefinish'],$quiz['limit_time'])) {
            return response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save')
            ]);
        }

        if (empty($template)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy template lần thi.',
            ]);
        }

        $question_id = $request->question_id;
        $questions = $template['questions'];

        $question = $questions[$question_id];
        $question['flag'] = $request->flag;

        $questions[$question_id] = $question;
        $template['questions'] = $questions;
        QuizAttempts::updateQuizData($attempt_id,$template);
        return response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save')
        ]);
    }

    public function saveLockedUserQuiz($quiz_id, $part_id, $attempt_id, Request $request) {

        $template = QuizAttempts::getQuizData($attempt_id);
        $quiz = $template['quiz'];
        $attempt = $template['attempt'];

        $quiz_register = QuizRegister::whereQuizId($quiz_id)
            ->where('part_id', $part_id)
            ->where('user_id', $attempt['user_id'])->first();
        $quiz_register->update([
                'locked' => 1,
            ]);

        session(['user_lock_'.$quiz_register->id => 1]);

        if (QuizAttempts::isAttemptFinish($attempt['timestart'],$attempt['timefinish'],$quiz['limit_time'])) {
            return response()->json([
                'status' => 'success',
                'message' => trans('laother.successful_save'),
                'redirect' => route('module.quiz.doquiz.index', [
                    'quiz_id' => $quiz_id,
                    'part_id' => $part_id,
                ]),
            ]);
        }
        $qids = (array) $request->input('q', []);
        $questions = $template['questions'];
        foreach ($qids as $index => $question_id){
            $question = $questions[$question_id];

            $anwsers = [];
            $matching = [];
            $score = 0;
            if ($question['type'] == 'essay'){
                if ($anwsers) {
                    $question['text_essay'] = $anwsers;
                    $question['selected'] = true;
                }
            }

            if ($question['type'] == 'matching'){
                if ($matching) {
                    $question['matching'] = $matching;
                    $question['selected'] = true;
                    /**********************/
                    $matching_select = $matching;
                    $correct_answers = [];
                    $answers = QuestionAnswer::where('question_id', '=', $question['question_id'])->get();
                    $count = 0;
                    foreach ($answers as $answer){
                        if ($matching_select[$answer->id] == $answer->matching_answer){
                            $count += 1;
                            $correct_answers[] = $answer->id;
                        }
                    }
                    if ($count == $answers->count()){
                        $score = ($question['score_group'] * $question['max_score']);
                    }

                    $question['correct_answers'] = $correct_answers;
                }
            }

            if ($question['type'] == 'multiple-choise') {
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /*************************/
                    if ($question['multiple'] == 0){
                        $true_answer = count(array_intersect($anwsers,$question['correct_answers']));
                        $score = ($question['score_group'] * $question['max_score']) * $true_answer;
                    }
                    elseif ($question['multiple'] == 1){
                        $answer_selected = $anwsers;
                        $answersOrg = $question['answers'];
                        $answersCorrect = $question['correct_answers'];

                        if (count($answer_selected) == count($answersOrg) && count($answersCorrect) < count($answersOrg)){ // Số câu tl đúng nhỏ hơn câu tl setup mà HV chọn hết
                            $score = 0;
                        }else{
                            $score = 0;
                            if($question['multiple_full_score'] == 1){
                                $total_correct = count(array_intersect($answer_selected, $answersCorrect));
                                if($total_correct == count($answersCorrect)){
                                    $score += ($question['score_group'] * $question['max_score']);
                                }
                            }else{
                                foreach ($answersOrg as $item){
                                    if(in_array($item['id'],$answer_selected))
                                        $score += (($question['score_group'] * $question['max_score']) * $item->percent_answer ) / 100;
                                }
                            }
                        }
                    }
                }
            }

            if ($question['type'] == 'fill_in'){
                if ($anwsers) {
                    $question['text_essay'] = $anwsers;
                    $question['selected'] = true;
                }
            }

            if ($question['type'] == 'fill_in_correct'){
                if ($anwsers) {
                    $question['text_essay'] = $anwsers;
                    $question['selected'] = true;
                    /****************/
                    $fill_in_correct_selected = $question['text_essay'];
                    $answersOrg = $question['answers'];
                    $count = 0;
                    $percent = ($answersOrg->count() > 0) ? 100/$answersOrg->count() : 0;
                    foreach ($answersOrg as $key => $answer){
                        if (\Str::lower($answer->fill_in_correct_answer) == \Str::lower($fill_in_correct_selected[$key])){
                            $count += $percent;
                        }
                    }
                    $score = (($question['score_group'] * $question['max_score']) * $count) / 100;
                }
                else{
                    $score = 0;
                }
            }
            if ($question['type'] == 'select_word_correct') {
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /******************/
                    $answer_selected = $question['answer'];
                    $true_answer = count(array_intersect($anwsers,$question['correct_answers']));
                    $totalAnswer = count($answer_selected);
                    $score_answer_true_avg = $totalAnswer>0 ? $true_answer/$totalAnswer : 0;
                    $score = ($question['score_group'] * $question['max_score']) * $score_answer_true_avg;
                }
            }

            if($question['type'] == 'drag_drop_marker'){
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /*************************/
                    $answer_selected = $anwsers;
                    $answersOrg = $question['answers'];

                    $count_total = 0; //đếm tổng đáp án có tọa độ
                    $count_correct = 0; //đếm tổng đáp án đúng
                    foreach ($answersOrg as $item){
                        $id = $item['id'];
                        if($item['marker_answer']){
                            $count_total += 1;

                            if($answer_selected[$id] == $item['marker_answer']){
                                $count_correct += 1;
                            }
                        }
                    }
                    $score_answer_true_avg = $count_total>0 ? $count_correct/$count_total : 0;
                    $score = ($question['score_group'] * $question['max_score']) * $score_answer_true_avg;
                }
            }

            if ($question['type'] == 'drag_drop_image') {
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /******************/
                    $true_answer = count(array_intersect($anwsers, $question['correct_answers']));
                    $totalAnswer = count($question['correct_answers']);
                    $score_answer_true_avg = $totalAnswer>0 ? $true_answer/$totalAnswer : 0;
                    $score = ($question['score_group'] * $question['max_score']) * $score_answer_true_avg;
                }
            }

            if ($question['type'] == 'drag_drop_document') {
                if ($anwsers) {
                    $question['answer'] = $anwsers;
                    $question['selected'] = true;
                    /******************/
                    $true_answer = count(array_intersect($anwsers, $question['correct_answers']));
                    $totalAnswer = count($question['correct_answers']);
                    $score_answer_true_avg = $totalAnswer>0 ? $true_answer/$totalAnswer : 0;
                    $score = ($question['score_group'] * $question['max_score']) * $score_answer_true_avg;
                }
            }

            $question['score'] = $score;
            $questions[$question_id] = $question;
        }
        $template['questions'] = $questions;
        QuizAttempts::updateQuizData($attempt_id,$template);
        return response()->json([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.quiz.doquiz.index', [
                'quiz_id' => $quiz_id,
                'part_id' => $part_id,
            ]),
        ]);
    }

}
