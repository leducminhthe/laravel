<?php
namespace App\Imports;
use App\Models\Categories\Area;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Notifications\ImportAreaHasFailed;
use Maatwebsite\Excel\Events\ImportFailed;
use Modules\Notify\Entities\NotifySend;
use Modules\Notify\Entities\NotifySendObject;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Categories\Unit;
use App\Models\AreaName;

class AreaImport implements ToModel, WithStartRow
{
    use Importable;
    public $imported_by;
    public $errors;

    public function __construct($user)
    {
        $this->imported_by = $user;
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;
        $level = Area::getMaxAreaLevel();
        $errors = [];

        // kiểm tra chức danh có thuộc đơn vị quản lý hay ko
        if(!Permission::isAdmin()){
            $userUnit = session()->get('user_unit');
            $user_role = UserRole::query()
                ->select(['c.unit_id', 'c.type', 'd.code', 'd.name'])->disableCache()
                ->from('el_user_role as a')
                ->join('el_role_has_permission_type as b', 'b.role_id', '=', 'a.role_id')
                ->join('el_permission_type_unit as c', 'c.permission_type_id', '=', 'b.permission_type_id')
                ->join('el_unit as d', 'd.id', '=', 'c.unit_id')
                ->where('a.user_id', '=', profile()->user_id)
                ->where('c.unit_id', '=', $userUnit)
                ->first();
            if($user_role->type == 'group-child') {
                $getArray = Unit::getArrayChild($user_role->code);
                array_push($getArray, $user_role->unit_id);
            } 
        }

        for ($i = 1; $i <= $level; $i++){
            if ($i == 1){
                $model = Area::firstOrNew(['code' => trim($row[1]), 'level' => $i]);
                $model->code = trim($row[1]);
                $model->name = trim($row[2]);
                $model->level = $i;
                $model->status = 1;
            }
            if ($i == 2 && !empty($row[3])){
                $model = Area::firstOrNew(['code' => trim($row[3]), 'level' => $i]);
                $model->code = trim($row[3]);
                $model->name = trim($row[4]);
                $model->parent_code = trim($row[1]);
                $model->level = $i;
                $model->status = 1;
            }
            if ($i == 3 && !empty($row[5])){
                $model = Area::firstOrNew(['code' => trim($row[5]), 'level' => $i]);
                $model->code = trim($row[5]);
                $model->name = trim($row[6]);
                $model->parent_code = trim($row[3]);
                $model->level = $i;
                $model->status = 1;
            }
            if ($i == 4 && !empty($row[7])){
                $model = Area::firstOrNew(['code' => trim($row[7]), 'level' => $i]);
                $model->code = trim($row[7]);
                $model->name = trim($row[8]);
                $model->parent_code = trim($row[5]);
                $model->level = $i;
                $model->status = 1;
            }
            if ($i == 5 && !empty($row[9])){
                $model = Area::firstOrNew(['code' => trim($row[9]), 'level' => $i]);
                $model->code = trim($row[9]);
                $model->name = trim($row[10]);
                $model->parent_code = trim($row[7]);
                $model->level = $i;
                $model->status = 1;
            }
            if (empty($model->code)) {
                $this->errors[] = 'Dòng '. $row[0] .': Mã không thể trống';
                $error = true;
                // $this->imported_by->notify(new ImportAreaHasFailed($errors));
            } else {
                $checkIssetArea = Area::where('code', $model->code)->exists();
                if($checkIssetArea) {
                    if($user_role->type == 'group-child') {
                        if(!in_array($model->unit_by, $getArray)) {
                            $areaName = AreaName::where('level', $i)->first();
                            $this->errors[] = 'Dòng '. $row[0] .': '. $areaName->name .' Không thuộc đơn vị quản lý: '. $user_role->name .'';
                            $error = true;
                        }
                    } else {
                        if($model->unit_by != $user_role->unit_id) {
                            $areaName = AreaName::where('level', $i)->first();
                            $this->errors[] = 'Dòng '. $row[0] .': '. $areaName->name .' Không thuộc đơn vị quản lý: '. $user_role->name .'';
                            $error = true;
                        }
                    }
                }
            }
            if($error) {
                return null;
            } else {
                $model->save();
            }
            
        }
    }

    public function startRow(): int
    {
        return 4;
    }
    public function chunkSize(): int
    {
        return 200;
    }
    // public function registerEvents(): array
    // {
    //     return [
    //         ImportFailed::class => function(ImportFailed $event) {
    //             $this->imported_by->notify(new ImportAreaHasFailed([$event->getException()->getMessage()]));
    //         },
    //     ];
    // }
}
