<?php

namespace App\Listeners\Offline;

use App\Events\Offline\GoActivity;
use App\Models\Categories\TrainingTeacher;
use Illuminate\Support\Facades\Auth;
use Modules\Offline\Entities\OfflineCourseActivity;
use Modules\Offline\Entities\OfflineCourseActivityHistory;
use Modules\Offline\Entities\OfflineRegister;

class ActivityHistory
{
    public function __construct()
    {
        //
    }

    public function handle(GoActivity $event)
    {
        $user_type = getUserType();
        $user_id = getUserId();

        $activity = OfflineCourseActivity::findOrFail($event->course_activity_id);
        $register = OfflineRegister::whereCourseId($event->course_id)
            ->where('user_id', '=', $user_id)
            ->where('status', '=', 1)
            ->first();

        $traning_teacher = TrainingTeacher::where('user_id', '=', $user_id)->first();

        $history = new OfflineCourseActivityHistory();
        $history->course_id = $event->course_id;
        $history->activity_id = $activity->activity_id;
        $history->course_activity_id = $activity->id;
        $history->user_id = $user_id;
        $history->user_type = $user_type;
        $history->register_id = $register ? $register->id : ($traning_teacher ? $traning_teacher->id : $user_id);
        $history->save();
    }
}
