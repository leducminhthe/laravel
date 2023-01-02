<?php

namespace App\Console\Commands;

use App\Models\Visits;
use App\Models\VisitsStatistic;
use Illuminate\Console\Command;
use App\Models\CountUserExperienceNavigate;
use  App\Models\Websockets;

class VisitStatistic extends Command
{
    protected $signature = 'command:visit_statistic';

    protected $description = 'thống kê truy cập người dùng và trình duyệt chạy vào lúc 2h tối (0 2 * * *)';
    protected $expression ='0 2 * * *';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->updateVisitsStatisticMonth();
        $this->updateVisitsStatisticDevice();
        $this->updateVisitsStatisticBrowser();
        $this->removeUserNavigateCount();
        $this->removeWebsocket();
    }

    private function updateVisitsStatisticMonth(){
        $month = (int)date('m');
        $year = (int)date('Y');
        $from = date('Y-m-01 00:00:01');
        $to = date('Y-m-t 23:59:00');
        $query = Visits::query();
        $query->select([
            'id',
            'platform',
            'browser',
            'visitor_id',
            'created_at',
        ]);

        $visit = $query->from('el_visits')
            ->whereBetween('created_at',[$from,$to])->count();

        VisitsStatistic::updateOrCreate(
            [
                'name'=>$month,
                'type'=>'M',
                'year'=> $year,
            ],
            [
                'name'=>$month,
                'type'=>'M',
                'year'=> $year,
                'value'=>$visit
            ]
        );
    }

    private function updateVisitsStatisticDevice(){
        $devices = Visits::query()
            ->from('el_visits')
            ->groupBy('device_cate')
            ->selectRaw('device_cate as name, count(1) as value')
            ->get();
        foreach ($devices as $item){
            VisitsStatistic::updateOrCreate(
                [
                    'name'=>$item->name,
                    'type'=>'device',
                ],
                [
                    'name'=>$item->name,
                    'type'=>'device',
                    'year'=> 0,
                    'value'=>$item->value
                ]
            );
        }
    }

    private function updateVisitsStatisticBrowser(){
        $devices = Visits::query()
            ->from('el_visits')
            ->groupBy('browser')
            ->selectRaw('browser as name, count(1) as value')
            ->get();
        foreach ($devices as $item){
            VisitsStatistic::updateOrCreate(
                [
                    'name'=>$item->name,
                    'type'=>'browser',
                ],
                [
                    'name'=>$item->name,
                    'type'=>'browser',
                    'year'=> 0,
                    'value'=>$item->value
                ]
            );
        }

    }

    private function removeUserNavigateCount(){
        CountUserExperienceNavigate::query()->update(['date_number_count' => 0]);
    }

    private function removeWebsocket() {
        Websockets::query()->delete();
    }
}
