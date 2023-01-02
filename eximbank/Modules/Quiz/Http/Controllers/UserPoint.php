<?php

namespace Modules\Quiz\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Quiz\Entities\Quiz;
use Modules\Quiz\Entities\QuizTimeFinishPoint;
use Modules\Quiz\Entities\QuizResult;

class UserPoint extends Controller
{

    public function showModalQuiz($course_id, $id=null) {
        $timefinish = [];
        $quiz= Quiz::find($course_id);
        $result = QuizResult::where('quiz_id', '=', $quiz->id)->whereNull('text_quiz')->first();
        $model = UserPointSettings::firstOrNew(['id' => $id]);
        if($id && $model->note == 'timefinish') {
            $timefinish = QuizTimeFinishPoint::where(['userpoint_setting_id' => $model->id])->get();
        }
        $start_date_course = $quiz->start_date;
        $end_date_course = $quiz->end_date;

        return view('quiz::backend.quiz.modal.complete-quiz',[
            "quiz"=>$quiz,
            "model"=>$model,
            "start_date_course"=>$start_date_course,
            "end_date_course"=>$end_date_course,
            'timefinish' => $timefinish,
            'result' => $result
        ]);

    }

    public function saveSettingQuiz(Request $request, $quiz_id) {
        $note = $request->note;

        if($request->type != 'timefinish') {
            if($note == 'timecompleted'){
                $this->validateRequest([
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'pvalue' => 'required',
                ], $request, UserPointSettings::getAttributeName());
            }
            if($note == 'score' || $note == 'attempt'){
                $this->validateRequest([
                    'min_score' => 'required',
                    'max_score' => 'required',
                    'pvalue' => 'required',
                ], $request, UserPointSettings::getAttributeName());
            }
                
            $start_date= strtotime($request->start_date.' '.$request->formhour.':'.$request->formmin.':00');
            $end_date= strtotime($request->end_date.' '.$request->tohour.':'.$request->tomin.':00');
            $min_score = $request->min_score;
            $max_score = $request->max_score;
    
            if($end_date < $start_date){
                json_message('Khoảng thời gian không đúng', 'error');
            }
    
            if($max_score < $min_score && $note == 'score'){
                json_message('Khoảng điểm không đúng', 'error');
            }
            if($max_score < $min_score && $note == 'attempt'){
                json_message('Số lần không đúng', 'error');
            }
    
            if($note == 'timecompleted'){
                $check1 = UserPointSettings::where('item_id', $quiz_id)
                ->where('item_type', 4)
                ->where('pkey', 'quiz_complete')
                ->where('start_date', '<=', $start_date)
                ->where('end_date', '>=', $start_date);
                if ($check1->exists()) {
                    json_message('Khoảng thời gian đã tồn tại', 'error');
                }
    
                $check2 = UserPointSettings::where('item_id', $quiz_id)
                ->where('item_type', 4)
                ->where('pkey', 'quiz_complete')
                ->where('start_date', '<=', $end_date)
                ->where('end_date', '>=', $end_date);
                if ($check2->exists()) {
                    json_message('Khoảng thời gian đã tồn tại', 'error');
                }
            }
    
            $complete = UserPointSettings::firstOrNew(['id' => $request->id]);
            $complete->pkey = 'quiz_complete';
            $complete->item_id = $quiz_id;
            $complete->item_type = 4;
            $complete->pvalue = $request->pvalue;
            $complete->start_date = $note == 'timecompleted' ? $start_date : null;
            $complete->end_date = $note == 'timecompleted' ? $end_date : null;
            $complete->min_score = ($note == 'score' || $note == 'attempt') ? $min_score : null;
            $complete->max_score = ($note == 'score' || $note == 'attempt') ? $max_score : null;
            $complete->note = $note;
            $complete->save();
        } else {
            $checkIsset = UserPointSettings::where(['pkey' => 'quiz_complete', 'note' => 'timefinish', 'item_id' => $quiz_id])->exists();
            if($checkIsset && !$request->id) {
                json_message('Điều kiện điểm thưởng đã tồn tại', 'error');
            }
            $this->validateRequest([
                'type_timefinish' => 'required',
                'score_time_finish.*' => 'required'
            ], $request, [
                'score_time_finish.*' => 'Cách tính điểm',
            ]);

            $complete = UserPointSettings::firstOrNew(['id' => $request->id]);
            $complete->pkey = 'quiz_complete';
            $complete->item_id = $quiz_id;
            $complete->item_type = 4;
            $complete->pvalue = 0;
            $complete->ref = $request->type_timefinish;
            $complete->note = $note;
            $complete->save();

            foreach ($request->score_time_finish as $key => $score_time_finish) {
                if (!$score_time_finish) {
                    json_message('Điểm đạt không được trống', 'error');
                }
                $save = QuizTimeFinishPoint::firstOrNew(['id' => $request->id_time[$key]]);
                $save->quiz_id = $quiz_id;
                $save->userpoint_setting_id = $complete->id;
                $save->rank = $key + 1;
                $save->score = $score_time_finish;
                $save->save();
            }
        }
        
        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.quiz.edit',["id"=>$quiz_id]).'?tabs=userpoint'
        ]);
    }

    public function deleteSetting($quiz_id, $id) {
        UserPointSettings::destroy($id);
        QuizTimeFinishPoint::where(['quiz_id' => $quiz_id, 'userpoint_setting_id' => $id])->delete();
        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
            'redirect' => route('module.quiz.edit',["id"=>$quiz_id]).'?tabs=userpoint'
        ]);
    }

    public function removeItemTimeUserpoint(Request $request, $quiz_id) {
        $delete = QuizTimeFinishPoint::where('id', $request->id)->delete();

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
        ]);
    }
}
