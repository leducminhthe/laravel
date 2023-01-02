<?php

namespace App\Console\Commands;

use App\Models\Automail;
use App\Models\Categories\TrainingTeacher;
use App\Models\Config;
use App\Models\ProfileView;
use Illuminate\Console\Command;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineSchedule;

class MailNotifyOfflineSchedule extends Command
{
    protected $signature = 'mail:notify_offline_schedule';

    protected $description = 'Đối tượng nhận: Giảng viên trong khoá học Tập trung <br> Thời gian gửi: Trước X ngày so với ngày bắt đầu. Chạy cron lúc 05h sáng (0 5 * * *)';

    protected $expression = '0 5 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $num_day = Config::getConfig('notify_offline_schedule');
        if($num_day){
            $today = date('Y-m-d 00:00:00');
            $todayXday = strtotime(date("Y-m-d", strtotime($today)) . " +{$num_day} day");
            $todayXday = strftime("%Y-%m-%d 00:00:00", $todayXday);
    
            $query = OfflineSchedule::query();
            $query->where('lesson_date', '=', $todayXday);
    
            $rows = $query->get(['id', 'course_id', 'teacher_main_id']);
            foreach ($rows as $row) {
                $course = OfflineCourse::find($row->course_id);
                $teacher = TrainingTeacher::find($row->teacher_main_id);

                $signature = getMailSignature($teacher->user_id);
                $profile = ProfileView::where('user_id', $teacher->user_id)->first();

                $automail = new Automail();
                $automail->template_code = 'notify_offline_schedule';
                $automail->params = [
                    'signature' => $signature,
                    'gender' => $profile->gender == 1 ? 'Nam' : 'Nữ',
                    'full_name' => $profile->full_name,
                    'firstname' => $profile->firstname,
                    'course_name' => $course->name,
                    'start_date' => get_date($course->start_date),
                    'end_date' => get_date($course->end_date),
                ];
                $automail->users = [$teacher->user_id];
                $automail->object_id = $row->id;
                $automail->object_type = 'notify_offline_schedule';
                $automail->check_exists = true;

                if ($automail->addToAutomail()) {
                    echo "mail:notify_offline_schedule \n";
                }
                else {
                    echo "mail:notify_offline_schedule not\n";
                }
    
            }
        }
    }
}
