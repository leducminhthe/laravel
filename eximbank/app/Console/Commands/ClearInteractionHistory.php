<?php

namespace App\Console\Commands;

use App\Models\InteractionHistory;
use App\Models\InteractionHistoryClear;
use Illuminate\Console\Command;

class ClearInteractionHistory extends Command
{
    protected $signature = 'clear:interaction_history';
    protected $description = 'Xóa lịch sử tương tác. 1 ngày chạy 1 lần lúc 5h (0 5 * * *)';
    protected $expression = '0 5 * * *';

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
        $interaction_history_clear = InteractionHistoryClear::orderByDesc('date_clear')->first();
        if(isset($interaction_history_clear) && $interaction_history_clear->date_clear == date('Y-m-d')){
            InteractionHistory::where('created_at', '<', $interaction_history_clear->date_clear)->delete();
        }
    }
}
