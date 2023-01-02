<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\ModelHistory\Entities\ModelHistory;

class ClearModelHistory extends Command
{
    protected $signature = 'modelhistoryclear:run';
    protected $description = 'Xóa lịch sử ghi nhận thao tác 1 tháng chạy 1 lần lúc 23h (0 23 1 */1 *)';
    protected $expression ='0 23 1 */1 *';
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $monthLatest = date("Y-m-d", strtotime("-2 month"));
        $clear = ModelHistory::whereDate('created_at', '<' , $monthLatest)->limit(5000)->delete();
    }
}
