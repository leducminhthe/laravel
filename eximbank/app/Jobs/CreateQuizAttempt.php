<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Quiz\Entities\QuizAttemptHistory;

class CreateQuizAttempt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $attempt_id;
    public $content;
    public function __construct($attempt_id,$content)
    {
        $this->attempt_id = $attempt_id;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        QuizAttemptHistory::create(['attempt_id'=>$this->attempt_id,'content'=>$this->content]);
    }
}
