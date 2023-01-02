<?php

namespace App\Console\Commands;

use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\Profile;
use Illuminate\Console\Command;
use Modules\Quiz\Entities\QuizRegister;
use Modules\Quiz\Entities\QuizResult;

class SqlManager extends Command
{
    protected $signature = 'sql:command {sql?}';

    protected $description = 'update database';
    protected $expression ='* * * * *';
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
        $sql = $this->argument('sql');
        if ($sql){
            \DB::statement($sql);
            $this->info('Cập nhật thành công');
        }
    }
}
