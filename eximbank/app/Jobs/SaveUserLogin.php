<?php

namespace App\Jobs;

use App\Models\Analytics;
use App\Models\LoginHistory as AppLoginHistory;
use App\Models\Visits;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\Quiz\Entities\QuizAttemptHistory;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointRewardLogin;

class SaveUserLogin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;
    public $agent;
    public $user_agent;
    public function __construct($user_id, $agent, $user_agent)
    {
        $this->user_id = $user_id;
        $this->agent = $agent;
        $this->user_agent = $user_agent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->saveHistLogin();
        $this->addPoint();
    }
    public function saveHistLogin(){
        AppLoginHistory::setLoginHistory($this->user_id);
        Visits::saveVisits($this->user_id,$this->agent,$this->user_agent);
        /* analytics  */
        $analytic = new Analytics();
        $analytic->user_id =  $this->user_id;
        $analytic->ip_address = request()->ip();
        $analytic->start_date = date('Y-m-d H:i:s');
        $analytic->day = date('Y-m-d');
        $analytic->save();
    }
    private function addPoint(){
        if ($this->user_id<=2)
            return;
        $date = date('Y-m-d');
        $get_reward_login = UserPointRewardLogin::where('start_date','<=',$date)->where('end_date','>=',$date)->first();
        if(isset($get_reward_login)) {
            $count_number_login = Analytics::where('user_id',$this->user_id)->where('day','>=',$get_reward_login->start_date)->where('day','<=',$get_reward_login->end_date)->where('day','=',$date)->count();
            if ($count_number_login % $get_reward_login->number_login == 0) {
                $content_reward = 'Nhận điểm thưởng khi đăng nhập từ ngày: '.get_date($get_reward_login->start_date,'d/m/Y').' đến ngày '.get_date($get_reward_login->end_date,'d/m/Y');

                $save_point_reward_view = new UserPointResult();
                $save_point_reward_view->user_id = $this->user_id;
                $save_point_reward_view->content = $content_reward;
                $save_point_reward_view->setting_id = 2;
                $save_point_reward_view->point = $get_reward_login->reward_point;
                $save_point_reward_view->type = 10;
                $save_point_reward_view->type_promotion = 1;
                $save_point_reward_view->save();

                $user_point_reward_view = PromotionUserPoint::firstOrNew(['user_id' => $this->user_id]);
                $user_point_reward_view->point = (int)$user_point_reward_view->point + (int)$get_reward_login->reward_point;
                $user_point_reward_view->level_id = PromotionLevel::levelUp($user_point_reward_view->point, $this->user_id);
                $user_point_reward_view->save();
            }
        }
    }
}
