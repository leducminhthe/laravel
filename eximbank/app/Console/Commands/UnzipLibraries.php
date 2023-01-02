<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Libraries\Entities\LibrariesFileZip;

class UnzipLibraries extends Command
{
    protected $signature = 'unzip-libraries';
    protected $description = 'Giải nén file zip thư viện. cron chạy 1 phút 1 lần (* * * * *)';
    protected $expression = "* * * * *";

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $query = LibrariesFileZip::whereNotIn('status', [0, 1]);

        if (!$query->exists()) {
            return;
        }

        $zip = new \ZipArchive();
        $storage = \Storage::disk(config('app.datafile.upload_disk'));
        $today = date('Y/m/d');

        $rows = $query->limit(1)->get();
        foreach ($rows as $row) {
            $row->update([
                'status' => 3,
            ]);

            if (!$storage->exists($row->origin_path)) {
                $row->update([
                    'status' => 0,
                    'error' => 'File not exists.',
                ]);

                continue;
            }

            $this->info('Unzip path ' . $row->origin_path);

            $res = $zip->open($storage->path($row->origin_path));

            if ($res === true) {
                $unzip_folder = $today . '/libraries/' . \Str::random(10);
                $scorm_folder = $storage->path($unzip_folder);

                if (!$storage->exists($unzip_folder)) {
                    \File::makeDirectory($scorm_folder, 0777, true);
                }

                $zip->extractTo($scorm_folder);
                $zip->close();

                $index_file = $this->scanIndexFile($scorm_folder);

                if (!$index_file) {
                    $row->update([
                        'status' => 0,
                        'error' => 'Cannot find index file.',
                    ]);

                    \File::deleteDirectory($scorm_folder);

                    continue;
                }

                $row->update([
                    'unzip_path' => $unzip_folder,
                    'index_file' => $index_file,
                    'status' => 1,
                ]);

                $this->info("Unziped to folder " . $unzip_folder);

            } else {
                $row->update([
                    'status' => 0,
                    'error' => 'Cannot not open file.',
                ]);
            }
        }
    }

    protected function scanIndexFile($scorm_folder) {
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

        if (\File::isDirectory($scorm_folder.'/scormcontent')){
            $files = \File::files($scorm_folder.'/scormcontent');
            $path = 'scormcontent';
        }elseif (\File::isDirectory($scorm_folder.'/scormdriver')) {
            $files = \File::files($scorm_folder.'/scormdriver');
            $path = 'scormdriver';
        }elseif (\File::isDirectory($scorm_folder.'/res')){
            $files = \File::files($scorm_folder.'/res');
            $path = 'res';
        }else{
            $files = \File::files($scorm_folder);
        }

        foreach ($files as $file) {
            if (in_array($file->getBasename(), $scan_disk)) {
                return $path ? ($path.'/'.$file->getBasename()) : $file->getBasename();
            }
        }

        $xml = simplexml_load_file($scorm_folder.'/imsmanifest.xml');
        if ($xml){
            $resource = $xml->resources->resource[0];
            $path = $resource->file['href'];
            if ($path){
                return $path;
            }
        }

        return false;
    }
}
