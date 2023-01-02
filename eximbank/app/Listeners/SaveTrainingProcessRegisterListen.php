<?php

namespace App\Listeners;

use App\Events\SaveTrainingProcessRegister;
use App\Models\Analytics;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Offline\Entities\OfflineCourse;
use App\Models\ProfileView;
use Modules\User\Entities\TrainingProcess;
use App\Models\Categories\Subject;

class SaveTrainingProcessRegisterListen implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(SaveTrainingProcessRegister $event)
    {
        $profile = ProfileView::where('user_id', '=', $event->id)->first(['title_code', 'title_name', 'unit_code', 'unit_name']);
        TrainingProcess::updateOrCreate(
            [
                'user_id' => $event->id,
                'user_type' => 1,
                'course_id' => $event->course->id,
                'class_id' => $event->class_id,
                'course_type' => $event->type
            ],
            [
                'user_id' => $event->id,
                'course_id' => $event->course->id,
                'class_id' => $event->class_id,
                'course_type' => $event->type,
                'course_code' => $event->course->code,
                'course_name' => $event->course->name,
                'subject_id' => $event->subject->id,
                'subject_code' => $event->subject->code,
                'subject_name' => $event->subject->name,
                'titles_code' => $profile->title_code,
                'titles_name' => $profile->title_name,
                'unit_code' => $profile->unit_code,
                'unit_name' => $profile->unit_name,
                'start_date' => $event->course->start_date,
                'end_date' => $event->course->end_date,
                'process_type' => 1,
                'certificate' => $event->course->cert_code,
            ]
        );
    }
}
