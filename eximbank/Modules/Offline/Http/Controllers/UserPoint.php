<?php

namespace Modules\Offline\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Modules\Offline\Entities\OfflineCourse;
use Modules\UserPoint\Entities\UserPointSettings;
use App\Models\CourseTabEdit;

class UserPoint extends Controller
{

    public function showModalComplete($course_id, $id=null) {
        $course= OfflineCourse::find($course_id);

        $model = UserPointSettings::firstOrNew(['id' => $id]);

        $start_date_course = $course->start_date;
        $end_date_course = $course->end_date;

        return view('offline::backend.offline.modal.complete-setting',["course"=>$course, "model"=>$model, "start_date_course"=>$start_date_course, "end_date_course"=>$end_date_course]);
    }

    public function showModalQuiz($course_id, $id=null) {

        $course= OfflineCourse::find($course_id);
        $model = UserPointSettings::firstOrNew(['id' => $id]);
        $start_date_course = $course->start_date;
        $end_date_course = $course->end_date;

        return view('offline::backend.offline.modal.complete-quiz',[
            "course"=>$course,
            "model"=>$model,
            "start_date_course"=>$start_date_course,
            "end_date_course"=>$end_date_course,
        ]);

    }

    public function saveSettingComplete(Request $request, $course_id) {
        $this->validateRequest([
            'start_date' => 'required',
            'end_date' => 'required',
            'pvalue' => 'required',
        ], $request, UserPointSettings::getAttributeName());

        $start_date = strtotime($request->start_date.' '.$request->formhour.':'.$request->formmin.':00');
        $end_date = strtotime($request->end_date.' '.$request->tohour.':'.$request->tomin.':00');

        if($start_date > $end_date){
            json_message('Khoảng thời gian không đúng', 'error');
        }

        if(!$request->id){
            $check1 = UserPointSettings::where('item_id', $course_id)
            ->where('item_type', 3)
            ->where('pkey', 'offline_complete')
            ->where('start_date', '<=', $start_date)
            ->where('end_date', '>=', $start_date);

            $check2 = UserPointSettings::where('item_id', $course_id)
                ->where('item_type', 3)
                ->where('pkey', 'offline_complete')
                ->where('start_date', '<=', $end_date)
                ->where('end_date', '>=', $end_date);
        
            if ($check1->exists() || $check2->exists()) {
                json_message('Khoảng thời gian đã tồn tại', 'error');
            }
        }
        
        if ($request->id) {
            $complete = UserPointSettings::find($request->id);
            $complete->pvalue = $request->pvalue;
            $complete->start_date = $start_date;
            $complete->end_date = $end_date;
            $complete->save();
        }
        else{
            $complete = new UserPointSettings();
            $complete->pkey = 'offline_complete';
            $complete->item_id = $course_id;
            $complete->item_type = 3;
            $complete->pvalue = $request->pvalue;
            $complete->start_date = $start_date;
            $complete->end_date = $end_date;
            $complete->save();
        }

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.offline.edit_userpoint', ['id' => $course_id])
        ]);

    }

    public function saveSettingQuiz(Request $request, $course_id) {

        if ($request->id){
            $this->validateRequest([
                'start_date' => 'required',
                'end_date' => 'required',
                'pvalue' => 'required',
            ], $request, UserPointSettings::getAttributeName());
        }
        else {
            $this->validateRequest([
                'start_date' => 'required',
                'end_date' => 'required',
                'pvalue' => 'required',
            ], $request, UserPointSettings::getAttributeName());
        }

        $start_date= strtotime($request->start_date.' '.$request->formhour.':'.$request->formmin.':00');
        $end_date= strtotime($request->end_date.' '.$request->tohour.':'.$request->tomin.':00');

        if ($request->id) {
            $complete = UserPointSettings::find($request->id);
            $complete->pvalue=$request->pvalue;
            $complete->start_date=$start_date;
            $complete->end_date=$end_date;
            $complete->save();
        }
        else{
            $complete = new UserPointSettings();
            $complete->pkey='offline_quiz_complete';
            $complete->item_id=$course_id;
            $complete->item_type=3;
            $complete->pvalue=$request->pvalue;
            $complete->start_date=$start_date;
            $complete->end_date=$end_date;
            $complete->save();
        }


        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.offline.edit_userpoint', ['id' => $course_id])
        ]);

    }

    public function saveSettingOthers(Request $request, $course_id) {

        $userpoints = $request->userpoint_others;

        foreach ($userpoints as $k => $v) {
            UserPointSettings::updateOrCreate([
                'pkey' => $k,
                'item_id' => $course_id,
                'item_type' => 3
            ], [
                'pvalue' => $v
            ]);
        }

        $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'userpoint']);
        $course_edit->course_id = $course_id;
        $course_edit->tab_edit = 'userpoint';
        $course_edit->course_type = 2;
        $course_edit->save();

        json_result([
            'status' => 'success',
            'message' => trans('laother.successful_save'),
            'redirect' => route('module.offline.edit_userpoint', ['id' => $course_id])
        ]);

    }

    public function deleteSetting($course_id, $id) {

        UserPointSettings::destroy($id);

        json_result([
            'status' => 'success',
            'message' => trans('laother.delete_success'),
            'redirect' => route('module.offline.edit_userpoint', ['id' => $course_id])
        ]);

    }

}
