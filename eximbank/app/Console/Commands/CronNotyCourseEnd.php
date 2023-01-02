<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CourseView;
use App\Models\CourseRegisterView;
use Modules\Notify\Entities\Notify;
use Modules\AppNotification\Helpers\AppNotification;
use Modules\Notify\Entities\NotifyTemplate;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;

class CronNotyCourseEnd extends Command
{
    protected $signature = 'command:noty_course_end';
    protected $description = 'KH sắp kết thúc. Gửi thông báo nhắc nhở những học viên tham gia KH nhưng chưa hoàn thành, chạy lúc 1h sáng (0 1 * * *)';
    protected $expression ='0 1 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $date = date('Y-m-d');
        $allcourse = CourseView::where(['isopen' => 1, 'status' => 1])->get(['course_id', 'code', 'name', 'end_date', 'start_date', 'course_type']);
        $nottify_template_online = NotifyTemplate::where('code', '=', 'online_reminder_01')->first(['title', 'content']);
        $nottify_template_offline = NotifyTemplate::where('code', '=', 'offline_reminder_01')->first(['title', 'content']);

        foreach ($allcourse as $key => $course) {
            $dayLatest = strtotime(date("Y-m-d", strtotime($course->end_date)) . " -2 day");
            $dayLatest = strftime("%Y-%m-%d", $dayLatest);
            if($date == $dayLatest) {
                if($course->course_type == 1) {
                    $name = 'online';
                    $url_go_course = route('module.online.detail_online', ['id' => $course->course_id]);
                    $total_activity = OnlineCourseActivity::where(['course_id' => $course->course_id])->count();
                } else {
                    $name = 'offline';
                    $url_go_course = route('module.offline.detail', ['id' => $course->course_id]);
                }

                $query = CourseRegisterView::query();
                $query->select([
                    'profile.gender',
                    'profile.firstname',
                    'profile.lastname',
                    'register.user_id',
                ]);
                $query->from('el_course_register_view as register');
                $query->join('el_profile as profile', 'profile.user_id', '=', 'register.user_id');
                $query->leftJoin('el_course_complete as complete', function($join) {
                    $join->on('register.course_type', '=', 'complete.course_type');
                    $join->on('register.course_id', '=', 'complete.course_id');
                    $join->on('register.user_id', '=', 'complete.user_id');
                });
                $query->whereNull('complete.id');
                $query->where(['register.course_id' => $course->course_id, 'register.course_type' => $course->course_type]);
                $userNotComplete = $query->get();

                foreach ($userNotComplete as $key => $user) {
                    $params = [
                        'Duration' => 2,
                        'courseType' => $name,
                        'courseCode' => $course->code,
                        'courseName' => $course->name,
                        'startDate' => $course->start_date,
                        'endDate' => $course->end_date,
                        'Gender' => $user->gender == '1' ? 'Anh' : 'Chị',
                        'FirstName' => $user->lastname .' '. $user->firstname,
                        'url' => $url_go_course,
                    ];

                    if($course->course_type == 1) {
                        $user_completed_activity = OnlineCourseActivityCompletion::where(['course_id' => $course->course_id, 'user_id' => $user->user_id, 'status' => 1])->count();
                        $params['Progress'] = ($user_completed_activity/$total_activity) * 100;
                        $params = json_encode($params);
                        $subject_notify = $this->mapParams($nottify_template_online->title, $params);
                        $content_notify = $this->mapParams($nottify_template_online->content, $params);
                    } else {
                        $params = json_encode($params);
                        $subject_notify = $this->mapParams($nottify_template_offline->title, $params);
                        $content_notify = $this->mapParams($nottify_template_offline->content, $params);
                    }
                    $url = $url_go_course;
                    
                    $model = new Notify();
                    $model->user_id = $user->user_id;
                    $model->subject = $subject_notify;
                    $model->content = $content_notify;
                    $model->url = $url;
                    $model->created_by = 0;
                    $model->save();

                    $content = \Str::words(html_entity_decode(strip_tags($content_notify)), 10);
                    $redirect_url = route('module.notify.view', [
                        'id' => $model->id,
                        'type' => 1
                    ]);

                    $notification = new AppNotification();
                    $notification->setTitle($subject_notify);
                    $notification->setMessage($content);
                    $notification->setUrl($redirect_url);
                    $notification->add($user->user_id);
                    $notification->save();
                }
            } else {
                continue;
            }
        }
    }

    public function mapParams($content, $params) {
        $params = json_decode($params);
        foreach ($params as $key => $param) {
            if ($key == 'url') {
                $content = str_replace('{'. $key .'}', '<a target="_blank" href="'. $param .'">liên kết này</a>', $content);
            } else {
                $content = str_replace('{'. $key .'}', $param, $content);
            }
        }
        return $content;
    }

    public function getParams($params, $key) {
        $params = json_decode($params);
        if (isset($params->{$key})) {
            return $params->{$key};
        }
        return null;
    }
}
