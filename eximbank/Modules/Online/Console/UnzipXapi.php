<?php

namespace Modules\Online\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use Modules\Online\Entities\OnlineCourseActivityXapi as ActivityXapi;

class UnzipXapi extends Command
{
    protected $signature = 'online:unzip-xapi';

    protected $description = 'Unzip xapi actitvity. cron chay 1 phút 1 lần (* * * * *)';
    protected $expression = "* * * * *";
    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $query = ActivityXapi::from('el_online_course_activity_xapi AS a')
            ->whereNotExists(function (Builder $builder) {
                $builder->select(['id'])
                    ->from('el_xapi AS b')
                    ->whereColumn('b.origin_path', '=', 'a.path')
                    ->whereIn('status', [0, 1]);
            });

        if (!$query->exists()) {
            return;
        }

        $zip = new \ZipArchive();
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $today = date('Y/m/d');

        $rows = $query->limit(1)->get();
        foreach ($rows as $row) {
            $row->xapi->update([
                'status' => 3,
            ]);

            if (!$storage->exists($row->path)) {

                $row->xapi->update([
                    'status' => 0,
                    'error' => 'File not exists.',
                ]);

                continue;
            }

            $this->info('Unzip path ' . $row->path);

            $res = $zip->open($storage->path($row->path));

            if ($res === true) {
                $unzip_folder = $today . '/xapi/' . Str::random(10);
                $xapi_folder = $storage->path($unzip_folder);

                if (!$storage->exists($unzip_folder)) {
                    \File::makeDirectory($xapi_folder, 0777, true);
                }

                $zip->extractTo($xapi_folder);
                $zip->close();

                $index_file = $this->scanIndexFile($xapi_folder);

                if (!$index_file) {
                    $row->scorm->update([
                        'status' => 0,
                        'error' => 'Cannot find index file.',
                    ]);

                    \File::deleteDirectory($xapi_folder);

                    continue;
                }

                $row->xapi->update([
                    'origin_path' => $row->path,
                    'unzip_path' => $unzip_folder,
                    'index_file' => $index_file,
                    'status' => 1,
                ]);

                $this->info("Unziped to folder " . $unzip_folder);

            } else {
                $row->xapi->update([
                    'status' => 0,
                    'error' => 'Cannot not open file.',
                ]);
            }
        }
    }

    protected function scanIndexFile($xapi_folder) {
        $scan_disk = [
            'index_scorm.html',
            'index_lms.html',
            'index_lms_html5.html',
            'index.html',
            'index.htm',
            'indexAPI.html',
            'index_TINCAN.html',
            'story.html',
            'story_html5.html',
        ];
        $path = '';

        if (\File::isDirectory($xapi_folder.'/scormcontent')){
            $files = \File::files($xapi_folder.'/scormcontent');
            $path = 'scormcontent';
        }elseif (\File::isDirectory($xapi_folder.'/scormdriver')) {
            $files = \File::files($xapi_folder.'/scormdriver');
            $path = 'scormdriver';
        }elseif (\File::isDirectory($xapi_folder.'/res')){
            $files = \File::files($xapi_folder.'/res');
            $path = 'res';
        }else{
            $files = \File::files($xapi_folder);
        }

        foreach ($files as $file) {
            if (in_array($file->getBasename(), $scan_disk)) {
                return $path ? ($path.'/'.$file->getBasename()) : $file->getBasename();
            }
        }

        return false;
    }
}
