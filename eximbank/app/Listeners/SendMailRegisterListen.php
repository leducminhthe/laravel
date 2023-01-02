<?php

namespace App\Listeners;

use App\Events\SendMailRegister;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Automail;
use Modules\User\Entities\TrainingProcess;

class SendMailRegisterListen implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(SendMailRegister $event)
    {
        if(empty($event->type_send_mail)) {
            foreach ($event->users as $user_id){
                $signature = getMailSignature($user_id);
    
                $automail = new Automail();
                $automail->template_code = 'approve_register_unit';
                $automail->params = [
                    'signature' => $signature,
                    'code' => $event->course->code,
                    'name' => $event->course->name,
                    'start_date' => get_date($event->course->start_date),
                    'end_date' => get_date($event->course->end_date),
                    'url' => route('module.training_unit.approve_course.course', ['id' => $event->course->id, 'type' => $event->type])
                ];
    
                $automail->users = [$user_id];
                $automail->object_id = $event->course->id;
                $automail->object_type = $event->type == 1 ? 'approve_online_register_unit' :'approve_offline_register_unit';
                $automail->addToAutomail();
            }
        } else {
            if ($event->status == 1 ) {
                if($event->type == 1) {
                    $trainingLocation = 'Elearning';
                    $url = route('module.online.detail_online', ['id' => $event->course->id]);
                } else {
                    $trainingLocation = $event->course->training_location ? $event->course->training_location->name : '';
                    $url = route('module.offline.detail', ['id' => $event->course->id]);
                }
                foreach ($event->users as $index => $user) {
                    TrainingProcess::where(['user_id' => $user->user_id, 'course_id' => $event->course->id, 'course_type' => $event->type])->update(['status' => $event->status]);

                    $signature = getMailSignature($user->user_id);
                    $automail = new Automail();
                    $automail->template_code = 'registered_course';
                    $automail->params = [
                        'signature' => $signature,
                        'gender' => $user->user->gender == '1' ? 'Anh' : 'Chị',
                        'full_name' => $user->user->full_name,
                        'firstname' => $user->user->firstname,
                        'course_code' => $event->course->code,
                        'course_name' => $event->course->name,
                        'course_type' => $event->type == 1 ? 'Online' : 'Tập trung',
                        'start_date' => get_date($event->course->start_date),
                        'end_date' => get_date($event->course->end_date),
                        'training_location' => $trainingLocation,
                        'url' => $url
                    ];
                    $automail->users = [$user->user_id];
                    $automail->check_exists = true;
                    $automail->check_exists_status = 0;
                    $automail->object_id = $event->course->id;
                    $automail->object_type = $event->type == 1 ? 'register_approved_online' : 'register_approved_offline';
                    $automail->addToAutomail();
                }
            } else {
                foreach ($event->users as $user) {
                    TrainingProcess::where(['user_id' => $user->user_id, 'course_id' => $event->course->id, 'course_type' => $event->type])->update(['status' => $event->status]);

                    $signature = getMailSignature($user->user_id);
                    $params = [
                        'signature' => $signature,
                        'Gender' => $user->user->gender == '1' ? 'Anh' : 'Chị',
                        'FirstName' => $user->user->full_name,
                        'courseCode' => $event->course->code,
                        'courseName' => $event->course->name
                    ];
                    $user_id = [$user->user_id];
                    $this->saveEmailDeniedRegister($params, $user_id,$user->id);
                }
            }
        }
    }

    public function saveEmailDeniedRegister(array $params,array $user_id,int $register_id)
    {
        $automail = new Automail();
        $automail->template_code = 'declined_enroll';
        $automail->params = $params;
        $automail->users = $user_id;
        $automail->check_exists = true;
        $automail->check_exists_status = 0;
        $automail->object_id = $register_id;
        $automail->object_type = $event->type == 1 ? 'declined_enroll_online' : 'declined_enroll_offline';
        $automail->addToAutomail();
    }
}
