<?php

namespace Modules\User\Console;

use App\Models\Analytics;
use App\Models\User;
use Illuminate\Console\Command;
use Modules\Promotion\Entities\PromotionLevel;
use Modules\Promotion\Entities\PromotionUserPoint;
use Modules\UserPoint\Entities\UserPointResult;
use Modules\UserPoint\Entities\UserPointRewardLogin;

class UserPoint extends Command
{

    protected $signature = 'command:add_point_login';

    protected $description = 'Tặng điểm khi đăng nhập chạy vào lúc 5phút/lần(*/10 * * * *)';
    protected $expression ='*/10 * * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->updateUserPointLogin();
    }
    private function updateUserPointLogin(){
        $user_id = profile()->user_id;
        if ($user_id<=2)
            return;
        $date = date('Y-m-d');
        $get_reward_login = UserPointRewardLogin::where('start_date','<=',$date)->where('end_date','>=',$date)->first();
        if(isset($get_reward_login)) {
            $count_number_login = Analytics::where('user_id',$user_id)->where('day','>=',$get_reward_login->start_date)->where('day','<=',$get_reward_login->end_date)->where('day','=',$date)->count();
            if ($count_number_login % $get_reward_login->number_login == 0) {
                $content_reward = 'Nhận điểm thưởng khi đăng nhập từ ngày: '.get_date($get_reward_login->start_date,'d/m/Y').' đến ngày '.get_date($get_reward_login->end_date,'d/m/Y');

                $save_point_reward_view = new UserPointResult();
                $save_point_reward_view->user_id = $user_id;
                $save_point_reward_view->content = $content_reward;
                $save_point_reward_view->setting_id = 2;
                $save_point_reward_view->point = $get_reward_login->reward_point;
                $save_point_reward_view->type = 10;
                $save_point_reward_view->type_promotion = 1;
                $save_point_reward_view->save();

                $user_point_reward_view = PromotionUserPoint::firstOrNew(['user_id' => $user_id]);
                $user_point_reward_view->point = (int)$user_point_reward_view->point + (int)$get_reward_login->reward_point;
                $user_point_reward_view->level_id = PromotionLevel::levelUp($user_point_reward_view->point, $user_id);
                $user_point_reward_view->save();
            }
        }
    }
}
