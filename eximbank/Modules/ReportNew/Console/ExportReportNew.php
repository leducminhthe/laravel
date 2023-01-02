<?php

namespace Modules\ReportNew\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Modules\ReportNew\Entities\HistoryExport;

class ExportReportNew extends Command
{
    protected $signature = 'report:export_new';

    protected $description = 'Xuất báo cáo mới 1 phút chạy 1 lần';
    protected $expression ="* * * * *";
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
                $class_name = "Modules\ReportNew\Export\\". $export->class_name . 'Export';
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
            $file_name = 'exports_new/'. date('Y/m/d') .'/report_'. $export->class_name .'_'. date('H-i-s') .'_' . $export->id .'.xlsx';

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
