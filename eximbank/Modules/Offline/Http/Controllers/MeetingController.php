<?php


namespace Modules\Offline\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Categories\Unit;
use App\Models\UserRole;
use App\Traits\ZoomMeetingTrait;
use App\Traits\TeamsMeetingTrait;
use Illuminate\Http\Request;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineCourseActivity;
use Modules\Offline\Entities\OfflineCourseActivityTeams;
use Modules\Offline\Entities\OfflineCourseActivityZoom;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineInviteRegister;
use Modules\Quiz\Entities\QuizPart;
use App\Models\CourseTabEdit;

class MeetingController extends Controller
{
    use TeamsMeetingTrait;
    public function index($course_id, Request $request)
    {
        $model = OfflineCourse::findOrFail($course_id);
        $course = $model;
        $offlineActivity = OfflineCourseActivity::where(['course_id'=>$course_id])->first();
        $meetingType = $offlineActivity->subject_id;
        if ($meetingType==1)
            $meetingZoom = OfflineCourseActivityZoom::where('course_id',$course_id)->first();
        elseif ($meetingType==2)
            $meetingTeams = OfflineCourseActivityTeams::where('course_id',$course_id)->first();
        $meeting = new \stdClass();
        $meeting->topic = $meetingType==1? $meetingZoom->topic: $meetingTeams->topic;
        $meeting->description = $meetingType==1? $meetingZoom->description: $meetingTeams->description;
        $meeting->start_time = $meetingType==1? $meetingZoom->start_time: $meetingTeams->start_time;
        $meeting->duration = $meetingType==1? $meetingZoom->duration: $meetingTeams->duration;
        $meeting->meeting_id = $meetingType==1? $meetingZoom->zoom_id: $meetingTeams->teams_id;
            $class = OfflineCourseClass::where('default',1)->first();
            return view('offline::backend.offline.form', [
                'model' => $model,
                'course' => $course,
                'course_id' => $course_id,
                'page_title' => trans('latraining.meeting_online'),
                'meeting' => $meeting,
                'meetingType' => $meetingType,
                'subject_id' => $offlineActivity->subject_id,
                'activity_id' => $offlineActivity->activity_id,
                'class' =>$class
            ]);
    }
    public function save(Request $request)
    {

        $meetingType = $request->input('meeting_type');
        $course_id = $request->input('id');
//        $activity_id = $request->input('activity_id');
        if($meetingType==1){
            $activity_id = $this->saveZoom($request);
            $subject_id = 1;
        }
        else{
            $activity_id = $this->saveTeams($request);
            $subject_id = 2;
        }
        OfflineCourseActivity::where(['course_id'=>$course_id])->where('subject_id','!=',$subject_id)->delete();
        $model = OfflineCourseActivity::firstOrNew(['course_id'=>$course_id,'activity_id'=>$activity_id,'subject_id'=>$meetingType]);
        if ($model->save()) {
            return json_result([
                'message' => trans('laother.update_successful'),
                'status' => 'success',
            ]);
        }

    }

    public function saveZoom(Request $request)
    {
        $course_id = $request->route('id');
        $model = OfflineCourseActivityZoom::firstOrNew(['course_id' => $course_id]);
        $data =[
            'topic' =>$request->name,
            'start_time' =>$request->start_time,
            'duration' =>$request->duration,
        ];
        $zoom = $this->createZoom($data);
        $model->topic = $request->name;
        $model->description = $request->description;
        $model->start_time = datetime_convert($request->start_time);
        $model->duration = $request->duration;
        $model->course_id = $course_id;
        $model->status = $zoom['data']->status;
        $model->join_url = $zoom['data']->join_url;
        $model->start_url = $zoom['data']->start_url;
        $model->password = $zoom['data']->password;
        $model->zoom_id = $zoom['data']->id;

        if ($model->save()) {
            $course_edit = CourseTabEdit::firstOrNew(['course_id' => $course_id, 'course_type' => 2, 'tab_edit' => 'meeting']);
            $course_edit->course_id = $course_id;
            $course_edit->tab_edit = 'meeting';
            $course_edit->course_type = 2;
            $course_edit->save();

            return json_result([
                'message' => trans('laother.update_successful'),
                'status' => 'success',
            ]);
        }

        return false;
    }
    public function saveTeams(Request $request)
    {
        $course_id = $request->route('id');
        $model = OfflineCourseActivityTeams::firstOrNew(['course_id' => $course_id]);
        $data =[
            'subject' =>$request->name,
            'start_time' =>$request->start_time,
            'duration' =>$request->duration,
        ];
        if ($model->exists)
            $teams =  $this->updateTeams($model->teams_id,$data);
        else
            $teams = $this->createTeams($data);
        $model->topic = $request->name;
        $model->description = $request->description;
        $model->start_time = datetime_convert($request->start_time);
        $model->end_time = date('Y-m-d H:i:s', strtotime(datetime_convert($request->start_time) .' + '. $request->duration.' minute'));
        $model->duration = $request->duration;
        $model->course_id = $course_id;
        $model->join_url = $teams['data']->joinUrl;
        $model->join_web_url = $teams['data']->joinWebUrl;
        $model->meeting_code = $teams['data']->meetingCode;
        $model->teams_id = $teams['data']->id;
        if ($model->save()) {
            return $model->id;
        }

        return false;
    }
    public function zoomtest(Request $request)
    {

        $meeting_id = $request->route('id');
        $zoom = $this->getParticipants($meeting_id);
        dd($zoom);
    }

    public function listmeetings(Request $request)
    {
        $this->list_meetings();
    }
}
