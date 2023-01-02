<?php

namespace App\Imports;

use App\Models\Categories\Area;
use App\Models\User;
use App\Models\Profile;
use App\Models\Categories\Titles;
use App\Models\Categories\Unit;
use App\Models\UserMeta;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Row;
use App\Notifications\ImportUserHasFailed;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Permission;
use App\Models\UserRole;

class UserImport implements ToModel, WithStartRow
{
    use Importable;
    public $imported_by;
    public $fail = 0;
    public $data;
    public $dataSuccess;
    public $checkIssetUser;
    public $dataIsset = [];
    public $dataNotIsset = [];
    public $success = 0;
    public $maxImport = 1;
    public $checkUniqueUsername = [];
    public $dataUserMeta = [];
    public $dataImport = [];

    public function __construct()
    {
        $this->errors = [];
        $this->imported_by = $user_id;
        $this->user_role = $user_role;
    }

    public function model(array $row)
    {
        if($row[1]) {
            $this->dataImport[] = ['name_import' => 'user', 'row' => json_encode($row), 'username' => trim($row[1]), 'title_code' => trim($row[8]), 'unit_code' => trim($row[9]), 'user_code' => trim($row[4])];
            return;
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 400;
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         ImportFailed::class => function(ImportFailed $event) {
    //             $this->imported_by->notify(new ImportUserHasFailed([$event->getException()->getMessage()]));
    //         },
    //     ];
    // }
}
