<?php

namespace App\Console\Commands;

use App\Models\Categories\TrainingTeacherCertificate;
use Illuminate\Console\Command;

class UpdateStatusTrainingTeacherCertificate extends Command
{
    protected $signature = 'command:update_status_training_teacher_certificate';

    protected $description = 'update trạng thái chứng chỉ của GV. 1 ngày chạy 1 lần lúc 5h sáng (0 5 * * *)';
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
        TrainingTeacherCertificate::query()
        ->where('status', 1)
        ->where('date_effective', '<', date('Y-m-d'))
        ->update([
            'status' => 0,
        ]);

    }
}
