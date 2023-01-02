<?php

namespace Modules\Offline\Console;

use App\Models\Categories\Titles;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Offline\Entities\OfflineCourseClass;
use Modules\Offline\Entities\OfflineRegister;
use Modules\Offline\Entities\SettingJoinOfflineCourse;

class SettingJoinOffline extends Command
{
    protected $signature = 'command:setting_join_offline {course_id?}';
    protected $description = 'chạy ghi danh tự động vào khóa học offline theo tab Thiết lập khoá học 20h mỗi ngày (0 22 * * *)';
    protected $expression = "0 22 * * *";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $date_first = date('Y-m-01 00:00:00');
        $date_now = date('Y-m-t 23:59:59');
        $param_course_id = $this->argument('course_id');

        // Lấy thiết lập trong tháng hiện tại
        $query = SettingJoinOfflineCourse::query();
        $query->where('auto_register', 1);
        $query->where('updated_at', '>=', $date_first);
        $query->where('updated_at', '<=', $date_now);

        if($param_course_id){
            $query->where('course_id', $param_course_id);
        }

        $rows = $query->get();
        foreach ($rows as $row) {
            $title_id = $row->title_id ? [$row->title_id] : Titles::whereGroup($row->title_rank_id)->pluck('id')->toArray();
            $date_register = $row->date_register ? now()->subDays($row->date_register) : now();
            $date_register_join_company = $row->date_register_join_company ? now()->subDays($row->date_register_join_company) : now();

            $profile = DB::table('el_profile')
                ->where('status', 1)
                ->whereIn('title_id', $title_id)
                ->where(function($sub) use($date_register, $date_register_join_company) {
                    $sub->orWhere(function($sub2) use($date_register) {
                        $sub2->whereNotNull('date_title_appointment');
                        $sub2->whereDate('date_title_appointment', '>=', $date_register);
                    });
                    $sub->orWhere(function($sub2) use($date_register_join_company) {
                        $sub2->whereNotNull('join_company');
                        $sub2->whereDate('join_company', '>=', $date_register_join_company);
                    });
                })
                ->get(['user_id', 'unit_id']);

            $class = OfflineCourseClass::where(['default' => 1, 'course_id' => $row->course_id])->first();
            foreach($profile as $item){
                $model = OfflineRegister::where(['user_id' => $item->user_id, 'course_id' => $row->course_id])->firstOrNew();
                if(!$model->id){
                    $model->user_id = $item->user_id;
                    $model->course_id = $row->course_id;
                    $model->class_id = $class->id;
                    $model->register_form = 2; //Hình thức ghi danh tự động
                    $model->status = 1;
                    $model->unit_by = $item->unit_id;
                    $model->save();
                }
            }
        }
    }

}
