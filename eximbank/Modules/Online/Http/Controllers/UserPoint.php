<?php

namespace Modules\Online\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Modules\Online\Entities\OnlineCourse;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Quiz\Entities\Quiz;
use App\Models\CourseTabEdit;

class UserPoint extends Controller
{
    public function showModalComplete($course_id, $type, $id=null) {
        $course = OnlineCourse::find($course_id);

        $model = UserPointSettings::firstOrNew(['id' => $id]);

        $start_date_course = $course->start_date;
        $end_date_course = $course->end_date;

        return view('online::backend.online.modal.complete-setting',[
            "course" => $course, 
            "model" => $model, 
            "start_date_course" => $start_date_course, 
            "end_date_course" => $end_date_course,
            'type' => $type
        ]);
    }

    public function showModalModule($course_id, $type, $id=null) {

        $course= OnlineCourse::find($course_id);
        $model = UserPointSettings::firstOrNew(['id' => $id]);
        $start_date_course = $course->start_date;
        $end_date_course = $course->end_date;

        $query = OnlineCourse::query();
        $query->select([
            'a.*', 
            'b.code AS code'
        ]);
        $query->from('el_online_course_activity AS a')
            ->leftJoin('el_online_activity AS b', 'b.id', '=', 'a.activity_id')
            ->where('a.course_id', '=', $course_id);

        $activities = $query->get();

        return view('online::backend.online.modal.complete-activity',[
            "course" => $course,
            "model" => $model,
            "start_date_course" => $start_date_course,
            "end_date_course" => $end_date_course,
            "activities" => $activities,
            'type' => $type
        ]);
    }

    public function saveSettingComplete(Request $request, $course_id, $type) {
        $this->validateRequest([
            'start_date' => 'required',
            'pvalue' => 'required',
        ], $request, UserPointSettings::getAttributeName());

        $start_date= strtotime($request->start_date.' '.$request->formhour.':'.$request->formmin.':00');
        $end_date= strtotime($request->end_date.' '.$request->tohour.':'.$request->tomin.':00');

        if(!$request->id){
            $check1 = UserPointSettings::where('item_id', $course_id)
            ->where('item_type', 2)
            ->where('pkey', 'online_complete')
            ->where('start_date', '<=', $start_date)
            ->where(function($sub) use ($start_date){
                $sub->orWhere('end_date','=', 0);
                $sub->orWhere('end_date', '>=', $start_date);
            });
            if ($check1->exists()) {
                json_message('Khoảng thời gian đã tồn tại', 'error');
            }
    
            if($request->end_date){
                $check2 = UserPointSettings::where('item_id', $course_id)
                ->where('item_type', 2)
                ->where('pkey', 'online_complete')
                ->where('start_date', '<=', $end_date)
                ->where(function($sub) use ($end_date){
                    $sub->orWhere('end_date', '=', 0);
                    $sub->orWhere('end_date', '>=', $end_date);
                });
    
                if ($check2->exists()) {
                    json_message('Khoảng thời gian đã tồn tại', 'error');
                }
            }    
        }
        
        $complete = UserPointSettings::firstOrNew(['id' => $request->id]);
        $complete->pkey = 'online_complete';
        $complete->item_id = $course_id;
        $complete->item_type = 2;
        $complete->pvalue = $request->pvalue;
        $complete->start_date = $start_date;
        $complete->end_date = $request->end_date ? $end_date : 0;
        $complete->save();

        if($type == 1) {
            $redirect = route('module.online.course_for_offline.edit_userpoint', ['id' => $course_id]);
        } else {
            $redirect = route('module.online.edit_userpoint', ['id' => $course_id]);
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => $redirect
        ]);

    }

    public function saveSettingActivities(Request $request, $course_id, $type) {
        $this->validateRequest([
            'ref' => 'required',
            'pvalue' => 'required',
        ], $request, [
            'ref' => 'Hoạt động',
            'pvalue' => 'Điểm',
        ]);

        $note = $request->note;

        if($note == 'timecompleted'){
            $this->validateRequest([
                'start_date' => 'required',
                'end_date' => 'required',
            ], $request, UserPointSettings::getAttributeName());
        }
        if($note == 'score' || $note == 'attempt'){
            $this->validateRequest([
                'min_score' => 'required',
                'max_score' => 'required',
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

        if($note == 'timecompleted' && !$request->id){
            $check1 = UserPointSettings::where('item_id', $course_id)
            ->where('item_type', 2)
            ->where('pkey', 'online_activity_complete')
            ->where('ref', $request->ref)
            ->where('start_date', '<=', $start_date)
            ->where('end_date', '>=', $start_date);
            if ($check1->exists()) {
                json_message('Khoảng thời gian đã tồn tại', 'error');
            }

            $check2 = UserPointSettings::where('item_id', $course_id)
            ->where('item_type', 2)
            ->where('pkey', 'online_activity_complete')
            ->where('ref', $request->ref)
            ->where('start_date', '<=', $end_date)
            ->where('end_date', '>=', $end_date);
            if ($check2->exists()) {
                json_message('Khoảng thời gian đã tồn tại', 'error');
            }
        }

        $complete = UserPointSettings::firstOrNew(['id' => $request->id]);
        $complete->pkey = 'online_activity_complete';
        $complete->item_id = $course_id;
        $complete->item_type = 2;
        $complete->pvalue = $request->pvalue;
        $complete->start_date = $note == 'timecompleted' ? $start_date : null;
        $complete->end_date = $note == 'timecompleted' ? $end_date : null;
        $complete->min_score = ($note == 'score' || $note == 'attempt') ? $min_score : null;
        $complete->max_score = ($note == 'score' || $note == 'attempt') ? $max_score : null;
        $complete->ref = $request->ref;
        $complete->note = $note;
        $complete->save();

        if($type == 1) {
            $redirect = route('module.online.course_for_offline.edit_userpoint', ['id' => $course_id]);
        } else {
            $redirect = route('module.online.edit_userpoint', ['id' => $course_id]);
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => $redirect
        ]);

    }

    public function saveSettingOthers(Request $request, $course_id, $type) {

        $userpoints = $request->userpoint_others;

        foreach ($userpoints as $k => $v) {
            UserPointSettings::updateOrCreate([
                'pkey' => $k,
                'item_id' => $course_id,
                'item_type' => 2
            ], [
                'pvalue' => $v
            ]);
        }

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 1, 'tab_edit' => 'userpoint']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'userpoint';
        $course_edit->course_type = 1;
        $course_edit->save();

        if($type == 1) {
            $redirect = route('module.online.course_for_offline.edit_userpoint', ['id' => $course_id]);
        } else {
            $redirect = route('module.online.edit_userpoint', ['id' => $course_id]);
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => $redirect
        ]);

    }

    public function deleteSetting($course_id, $type, $id) {

        UserPointSettings::destroy($id);

        if($type == 1) {
            $redirect = route('module.online.course_for_offline.edit_userpoint', ['id' => $course_id]);
        } else {
            $redirect = route('module.online.edit_userpoint', ['id' => $course_id]);
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
            'redirect' => $redirect
        ]);

    }

    public function showModalQuiz($course_id, $quiz_id, $id=null) {

        $quiz= Quiz::find($quiz_id);
        $model = UserPointSettings::firstOrNew(['id' => $id]);
        $start_date_course = $quiz->start_date;
        $end_date_course = $quiz->end_date;

        return view('online::backend.quiz.modal.complete-quiz',[
            'course_id' => $course_id,
            "quiz"=>$quiz,
            "model"=>$model,
            "start_date_course"=>$start_date_course,
            "end_date_course"=>$end_date_course,
        ]);

    }

    public function saveSettingQuiz(Request $request, $course_id, $quiz_id) {
        $note = $request->note;

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

        if($note == 'timecompleted' && !($request->id)){
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

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.online.quiz.edit',['course_id' => $course_id, "id"=>$quiz_id]).'?tabs=promotion'
        ]);

    }

    public function deleteSettingQuiz($course_id, $quiz_id, $id) {

        UserPointSettings::destroy($id);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
            'redirect' => route('module.online.quiz.edit',['course_id' => $course_id, "id"=>$quiz_id]).'?tabs=promotion'
        ]);

    }
}
