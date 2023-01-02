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

class SyncTable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fromTable;
    public $fromColumn;
    public $recordChange;
    public $sync_table_setting_id;
    public $sync_table_id;
    public function __construct($fromTable, $fromColumn, $sync_table_setting_id, $recordChange, $sync_table_id)
    {
        $this->fromTable = $fromTable;
        $this->fromColumn = $fromColumn;
        $this->sync_table_setting_id = $sync_table_setting_id;
        $this->recordChange = $recordChange;
        $this->sync_table_id = $sync_table_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sync();
    }
    public function sync(){
        $setting = \DB::table('sync_table_setting')->where(['id'=>$this->sync_table_setting_id])->first();
            $dataChange = \DB::table("{$setting->from_table}")->select("{$setting->from_column}")->where(['id'=>$this->recordChange])->value("{$setting->from_column}");
            \DB::statement("update {$setting->to_table} set {$setting->to_column}='$dataChange' where {$setting->relationship}={$this->recordChange}");
            \Modules\SyncTable\Entities\SyncTable::destroy($this->sync_table_id);
    }
}
