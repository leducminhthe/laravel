<?php

namespace App\Console\Commands;

use App\Models\Automail;
use App\Models\Config;
use App\Models\ProfileView;
use Illuminate\Console\Command;
use Modules\Offline\Entities\OfflineCourse;
use Modules\Offline\Entities\OfflineResult;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineRegister;
use Modules\Online\Entities\OnlineResult;

class MailNotifyCourseDeadline1 extends Command
{
    protected $signature = 'mail:notify_course_deadline_1';

    protected $description = 'Thông báo sắp đến hạn khóa học. Đối tượng nhận: Học viên. Thời gian gửi: Trước X ngày so với ngày kết thúc. Chạy cron lúc 05h sáng (0 5 * * *)';

    protected $expression = '0 5 * * *';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $num_day = Config::getConfig('notify_course_deadline_1');
        if($num_day){
            $today = date('Y-m-d 00:00:00');
            $todayXday = strtotime(date("Y-m-d", strtotime($today)) . " +{$num_day} day");
            $todayXday = strftime("%Y-%m-%d 23:59:59", $todayXday);

            $query = OnlineCourse::query();
            $query->where('status', '=', 1)
                ->whereNotNull('end_date')
                ->where('end_date', '=', $todayXday);
    
            $rows = $query->get(['id', 'code', 'name', 'end_date']);
            foreach ($rows as $row) {
                $user_complete = OnlineResult::whereCourseId($row->id)->whereResult(1)->pluck('user_id')->toArray();
    
                $regquery = OnlineRegister::query();
                $regquery->where('course_id', '=', $row->id);
                $regquery->where('status', '=', 1);
                $regquery->whereNotIn('user_id', $user_complete);
                $users = $regquery->get(['user_id']);
    
                foreach ($users as $user){
                    $signature = getMailSignature($user->user_id);
                    $profile = ProfileView::where('user_id', $user->user_id)->first();
    
                    $automail = new Automail();
                    $automail->template_code = 'notify_course_deadline_1';
                    $automail->params = [
                        'signature' => $signature,
                        'gender' => $profile->gender == 1 ? 'Nam' : 'Nữ',
                        'full_name' => $profile->full_name,
                        'firstname' => $profile->firstname,
                        'course_code' => $row->code,
                        'course_name' => $row->name,
                        'end_date' => get_date($row->end_date),
                        'so_ngay' => $num_day,
                        'url' => route('module.online.detail_online', ['id' => $row->id]),
                    ];
                    $automail->users = [$user->user_id];
                    $automail->object_id = $row->id;
                    $automail->object_type = 'notify_course_deadline_1';
                    $automail->check_exists = true;
    
                    if ($automail->addToAutomail()) {
                        echo "mail:notify_course_deadline_1 ". $row->name ."\n";
                    }
                    else {
                        echo "mail:notify_course_deadline_1 not ". $row->name ."\n";
                    }
                }
            }
    
            $query = OfflineCourse::query();
            $query->where('status', '=', 1)
                ->where('end_date', '=', $todayXday);
    
            $rows = $query->get(['id', 'code', 'name', 'end_date']);
            foreach ($rows as $row) {
                $user_complete = OfflineResult::whereCourseId($row->id)->whereResult(1)->pluck('user_id')->toArray();

                $regquery = OnlineRegister::query();
                $regquery->where('course_id', '=', $row->id);
                $regquery->where('status', '=', 1);
                $regquery->whereNotIn('user_id', $user_complete);
                $users = $regquery->get(['user_id']);
    
                foreach ($users as $user){
                    $signature = getMailSignature($user->user_id);
                    $profile = ProfileView::where('user_id', $user->user_id)->first();
    
                    $automail = new Automail();
                    $automail->template_code = 'notify_course_deadline_1';
                    $automail->params = [
                        'signature' => $signature,
                        'gender' => $profile->gender == 1 ? 'Nam' : 'Nữ',
                        'full_name' => $profile->full_name,
                        'firstname' => $profile->firstname,
                        'course_code' => $row->code,
                        'course_name' => $row->name,
                        'end_date' => get_date($row->end_date),
                        'so_ngay' => $num_day,
                        'url' => route('module.offline.detail', ['id' => $row->id]),
                    ];
                    $automail->users = [$user->user_id];
                    $automail->object_id = $row->id;
                    $automail->object_type = 'notify_course_deadline_1';
                    $automail->check_exists = true;
    
                    if ($automail->addToAutomail()) {
                        echo "mail:notify_course_deadline_1 ". $row->name ."\n";
                    }
                    else {
                        echo "mail:notify_course_deadline_1 not ". $row->name ."\n";
                    }
                }
    
            }
        }
    }
}
