<?php

namespace Modules\Online\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Online\Entities\OnlineCourse;
use Modules\Online\Entities\OnlineCourseActivity;
use Modules\Online\Entities\OnlineCourseActivityCompletion;
use Modules\UserPoint\Entities\UserPointSettings;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Promotion\Entities\PromotionLevel;

class PromotionActivity extends Command
{
    protected $signature = 'online:promotion_activity';

    protected $description = 'Điểm thưởng hoạt động online 1 phút 1 lần (* * * * *)';
    protected $expression = "* * * * *";
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now_sub_2_hour = Carbon::make(now())->subHours(2)->toDateTimeString();

        $rows = OnlineCourseActivityCompletion::query()
            ->where('status', 1)
            ->where('created_at', '>=', $now_sub_2_hour)
            ->get();

        foreach ($rows as $row) {
            $check = UserPointResult::query()
                ->leftJoin('el_userpoint_settings', 'el_userpoint_settings.id', '=', 'el_userpoint_result.setting_id')
                ->where('el_userpoint_settings.item_id', '=', $row->course_id)
                ->where('el_userpoint_settings.item_type', '=', 2)
                ->where('el_userpoint_settings.pkey','=', 'online_activity_complete')
                ->where('el_userpoint_result.user_id', '=', $row->user_id)
                ->where('el_userpoint_result.ref', '=', $row->activity_id);
            if($check->exists()){
                continue;
            }

            $course = OnlineCourse::find($row->course_id);
            $course_activity = OnlineCourseActivity::find($row->activity_id);

            $user_point_setting = UserPointSettings::whereItemId($row->course_id)
                ->where('item_type', '=', 2)
                ->where('pkey','=', 'online_activity_complete')
                ->where('ref', '=', $row->activity_id)
                ->get();
            foreach($user_point_setting as $item){
                if($item->note == 'timecompleted'){
                    $time = strtotime($row->created_at);

                    if($time >= $item->start_date && $time <= $item->end_date){
                        $note = 'Hoàn thành hoạt động <b>'. $course_activity->name .'</b> của khóa học online <b>'. $course->name .' ('. $course->code .')</b>';

                        $exists = UserPointResult::where("setting_id","=",$item->id)->where("user_id","=",$row->user_id)->whereNull("type")->first();
                        if(!$exists){
                            UserPointResult::create([
                                'setting_id' => $item->id,
                                'user_id' => $row->user_id,
                                'content' => $note,
                                'point' => $item->pvalue,
                                'ref' => $row->activity_id,
                                'type_promotion' => 0,
                            ]);

                            $user_point = PromotionUserPoint::firstOrNew(['user_id' => $row->user_id]);
                            $user_point->point = $user_point->point + $item->pvalue;
                            $user_point->level_id = PromotionLevel::levelUp($user_point->point, $row->user_id);
                            $user_point->save();
                        }
                    }
                }
            }
        }
    }
}
