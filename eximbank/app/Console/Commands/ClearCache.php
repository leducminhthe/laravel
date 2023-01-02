<?php

namespace App\Console\Commands;

use App\Models\Categories\Titles;
use App\Models\Profile;
use Illuminate\Console\Command;

class ClearCache extends Command
{
    protected $signature = 'cacheclear:run';
    protected $description = 'Xóa cache chạy 1 tiếng 1 lần (0 * * * *)';
    protected $expression ='0 * * * *';
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
        \Artisan::call('cache:clear');
    }
}
