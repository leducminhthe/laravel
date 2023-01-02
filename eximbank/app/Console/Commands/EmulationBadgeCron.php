<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CourseRegisterView;
use Modules\EmulationBadge\Entities\EmulationBadge;
use Modules\EmulationBadge\Entities\ArmorialEmulationBadge;
use Modules\EmulationBadge\Entities\CourseEmulationBadge;
use Modules\EmulationBadge\Entities\UserEmulationBadge;
use App\Models\CourseView;
use App\Models\Profile;
use Modules\Online\Entities\OnlineCourseActivityHistory;
use Modules\Online\Entities\OnlineResult;
use Modules\Online\Entities\OnlineCourseComplete;

class EmulationBadgeCron extends Command
{
    protected $signature = 'command:emulation_badge';

    protected $description = 'cập nhật huy hiệu chạy vào lúc 1h tối (0 1 * * *)';
    protected $expression ='0 1 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle() {
        UserEmulationBadge::truncate();
        $model = EmulationBadge::query();
        $model->select(['*']);
        $model->where('start_time', '<=', date('Y-m-d'));
        $model->where(function ($sub){
            $sub->whereNull('end_time');
            $sub->orWhere('end_time', '>=', date('Y-m-d'));
        });
        $model->where('status', 1);
        $emulationBadges = $model->get();
        foreach ($emulationBadges as $key => $emulation) {
            // dd(strtotime($emulation->start_time));
            $armorialType1 = ArmorialEmulationBadge::where(['emulation_badge_id' => $emulation->id, 'type' => 1])->orderBy('level', 'asc')->pluck('id')->toArray();
            $armorialType2 = ArmorialEmulationBadge::where(['emulation_badge_id' => $emulation->id, 'type' => 2])->orderBy('level', 'asc')->pluck('id')->toArray();
            $armorialType3 = ArmorialEmulationBadge::where(['emulation_badge_id' => $emulation->id, 'type' => 3])->orderBy('level', 'asc')->pluck('id')->toArray();

            $countArmorialType1 = count($armorialType1);
            $countArmorialType2 = count($armorialType2);
            $countArmorialType3 = count($armorialType3);

            $courseId = CourseEmulationBadge::where('emulation_badge_id', $emulation->id)->pluck('course_id')->toArray();

            // HỌC NHANH NHẤT
            if(!empty($armorialType1)) {
                $timeimeLearns = OnlineCourseActivityHistory::select('user_id')->whereIn('course_id', $courseId)->groupBy('user_id')->orderBy('created_at', 'asc')->take($countArmorialType1)->get();
                foreach ($timeimeLearns as $keyTime => $time) {
                    // $userGetArmorialType1[] = [$emulation->id, $time->user_id, $armorialType1[$keyTime]];
                    $this->saveUserEmulation($emulation->id, $time->user_id, $armorialType1[$keyTime]);
                }
            }

            // ĐIỂM CAO NHẤT
            if(!empty($armorialType2)) {
                $scoreLearn = OnlineResult::select('user_id')->whereIn('course_id', $courseId)->whereNotNull('score')->where('result', 1)->groupBy('user_id')->orderBy('score', 'desc')->take($countArmorialType2)->get();
                foreach ($scoreLearn as $keyScore => $score) {
                    // $userGetArmorialType2[] = [$emulation->id, $score->user_id, $armorialType2[$keyScore]];
                    $this->saveUserEmulation($emulation->id, $score->user_id, $armorialType2[$keyScore]);
                }
            }

            // HOÀN THÀNH SỚM NHẤT
            if(!empty($armorialType3)) {
                $completedLearn = OnlineCourseComplete::select('user_id')->whereIn('course_id', $courseId)->groupBy('user_id')->orderBy('time_complete', 'asc')->take($countArmorialType3)->get();
                foreach ($completedLearn as $keyCompleted => $completed) {
                    // $userGetArmorialType3[] = [$emulation->id, $completed->user_id, $armorialType3[$keyCompleted]];
                    $this->saveUserEmulation($emulation->id, $completed->user_id, $armorialType3[$keyCompleted]);
                }
            }
        }
    }

    public function saveUserEmulation($emulation_badge_id, $user_id, $armorial_id) {
        $save = new UserEmulationBadge();
        $save->emulation_badge_id = $emulation_badge_id;
        $save->user_id = $user_id;
        $save->armorial_id = $armorial_id;
        $save->save();
    }
}
