<?php

namespace Modules\Report\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Modules\Report\Entities\HistoryExport;

class ExportReport extends Command
{
    protected $signature = 'report:export';

    protected $description = 'Xuất báo cáo 1 phút chạy 1 lần';

    protected $max_process = 10;

    public function __construct() {
        parent::__construct();
    }

    public function handle() {

        $count_process = HistoryExport::where('status', '=', 3)->count();

        if ($count_process >= $this->max_process) {
            return;
        }

        $exports = HistoryExport::where('status', '=', 2)
            ->limit(1)
            ->get();

        foreach ($exports as $key => $export){
            if ($export->class_name == 'User'){
                $class_name = "App\Exports\\". $export->class_name . 'Export';
            }else{
                $class_name = "Modules\Report\Export\\". $export->class_name . 'Export';
            }

            if (!class_exists($class_name)) {
                HistoryExport::where('id', '=', $export->id)
                    ->update([
                        'status' => 0,
                        'error' => 'Class not exists.',
                    ]);

                continue;
            }

            $request = json_decode($export->request);
            $file_name = 'exports/'. date('Y/m/d') .'/report_'. $export->class_name .'_'. date('H-i-s') .'_' . $export->id .'.xlsx';

            HistoryExport::where('id', '=', $export->id)
                ->update([
                    'status' => 3,
                    'file_name' => $file_name,
                ]);

            try {

                $report = new $class_name($request);
                $report->store($file_name);

                HistoryExport::where('id', '=', $export->id)
                    ->update([
                        'status' => 1,
                    ]);
            }
            catch (\Exception $exception) {
                HistoryExport::where('id', '=', $export->id)
                    ->update([
                        'status' => 0,
                        'error' => $exception->getMessage(),
                    ]);
            }
        }

    }
}
