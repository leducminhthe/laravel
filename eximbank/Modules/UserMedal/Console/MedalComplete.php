<?php

namespace Modules\UserMedal\Console;

use App\Models\Automail;
use App\Models\CourseComplete;
use App\Models\Profile;
use Arcanedev\LogViewer\Entities\Log;
use Illuminate\Console\Command;
use Modules\UserMedal\Entities\UserMedalResult;
use Modules\UserMedal\Entities\UserMedalCompleted;
use Modules\UserMedal\Entities\UserMedalSettings;
use Modules\UserMedal\Entities\UserMedalSettingsItems;

class MedalComplete extends Command
{
    protected $signature = 'usermedal:complete';

    protected $description = 'Hoàn thành CTTD 1 phút 1 lần (* * * * *)';
    protected $expression = "* * * * *";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $query = UserMedalSettings::query();
        $query->where("end_date",">=",time());
        $rows = $query->get();

        foreach ($rows as $row) {

            $totalItems = UserMedalSettingsItems::where("setting_id","=",$row->id)->where("item_id",">","0")->count();
            \DB::statement("SET SQL_MODE=''");
            $query = UserMedalResult::query();
            $query->from('el_usermedal_result AS result')
                ->join('el_usermedal_settings_items AS items', 'items.id', '=', 'result.settings_items_id')
                ->where("items.setting_id","=",$row->id)
                ->groupBy("result.user_id")
                ->having(\DB::raw('count(result.user_id)'), '>=', $totalItems);

            $result = $query->get();

            foreach ($result as $rs) {

                $query = UserMedalResult::query();
                $query->select(\DB::raw("AVG(point) as average_point"));
                $query->from('el_usermedal_result AS result')
                    ->join('el_usermedal_settings_items AS items', 'items.id', '=', 'result.settings_items_id')
                    ->where("setting_id","=",$row->id)
                    ->where("user_id","=",$rs->user_id)
                    ->groupBy("result.user_id");
                $point =$query->pluck("average_point")->first();

                $setting_items_point = UserMedalSettingsItems::where("setting_id","=",$row->id)->where("item_type","=","5")->where("min_score", '<=', $point)
                    ->where("max_score", '>=', $point)->first();

                if(!empty($setting_items_point)){
                    $settings_id = $row->id;
                    $settings_items_id_got = $setting_items_point->id;
                    $exists=UserMedalCompleted::where("settings_id","=",$settings_id)->where("user_id","=",$rs->user_id)->first();

                    if($exists){
                        $model= UserMedalCompleted::find($exists->id);
                        $model->settings_items_id_got = $settings_items_id_got;
                        $model->point = $point;
                        $model->user_id = $rs->user_id;
                        $model->save();
                    }else{
                        UserMedalCompleted::create([
                            'settings_id' => $settings_id,
                            'settings_items_id_got' => $settings_items_id_got,
                            'user_id' => $rs->user_id,
                            'point' => $point
                        ]);
                    }
                }

            }

        }

    }

}
