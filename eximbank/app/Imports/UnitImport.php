<?php
namespace App\Imports;
use App\Models\Categories\Unit;

use App\Models\User;
use App\Models\UnitView;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Notifications\ImportUnitHasFailed;
use Maatwebsite\Excel\Events\ImportFailed;
use Modules\Notify\Entities\NotifySend;
use Modules\Notify\Entities\NotifySendObject;
use App\Models\Categories\UnitType;
use App\Models\Profile;
use App\Models\Categories\UnitManager;

class UnitImport implements ToModel, WithStartRow
{
    use Importable;
    public $imported_by;
    public $errors;

    public function __construct($user, $type_import)
    {
        $this->errors = [];
        $this->imported_by = $user;
        $this->type_import = $type_import;
    }

    public function model(array $row)
    {
        $unit_code =3; $unit_name=4; $unit_type=5; $unit_manager=6; $step=4; $stt =0;
        $level = Unit::getMaxUnitLevel();
        $errors = [];
        /** check loại đơn vị */
        for ($i = 1; $i <= $level; $i++) {
            if (isset($row[$unit_type])) {
                ${'checkUnitType'.$i} = UnitType::where('name', 'like', '%' . trim($row[$unit_type]) . '%')->first();
                if (empty(${'checkUnitType'.$i})) {
                    $this->errors[] = 'Dòng ' . $row[$stt] . ': Loại đơn vị không đúng';
                    return null;
                }
            }
            $unit_type+=$step;
        }
        $data = [];
        // ktra Mã người quản lý
        for ($i = 1; $i <= $level; $i++) {
            if (isset($row[$unit_manager])) {
                $profiles_code = explode(',',$row[$unit_manager]);
                foreach($profiles_code as $key => $profile_code) {
                    if($this->type_import == 1) {
                        $name_type = 'Mã nhân viên';
                        $checkProfileCode = Profile::where('code', '=', $profile_code)->first(['user_id', 'code']);
                    } else if ($this->type_import == 2) {
                        $name_type = 'Username';
                        $checkProfileCode = Profile::query()
                        ->select(['profile.unit_id', 'profile.user_id', 'profile.code'])
                        ->from('el_profile as profile')
                        ->join('user', 'user.id', '=', 'profile.user_id')
                        ->where('user.username', '=', $profile_code)
                        ->first();
                    } else {
                        $name_type = 'Email';
                        $checkProfileCode = Profile::where('email', '=', $profile_code)->first(['user_id', 'code']);
                    }

                    if(!isset($checkProfileCode)) {
                        $this->errors[] = 'Dòng '. $row[$stt] .': '. $name_type .' không đúng';
                        return null;
                    } else {
                        $data[$i][$profile_code] = [$checkProfileCode->user_id, $checkProfileCode->code];
                    }
                }
            }
            $unit_manager+=$step;
        }

        // save company level 0
        $model = Unit::firstOrNew(['code' => trim($row[1]), 'level' => 0]);
        $model->code = trim($row[1]);
        $model->name = trim($row[2]);
        $model->level = 0;
        $model->status = 1;
        $model->save();
        
        $unit_type=5; $unit_manager=6;
        for ($i = 1; $i <= $level; $i++){
            if (!empty($row[$unit_code])){
                $model = Unit::firstOrNew(['code' => trim($row[$unit_code]), 'level' => $i]);
                $model->code = trim($row[$unit_code]);
                $model->name = trim($row[$unit_name]);
                $model->type = !empty(${'checkUnitType'.$i}) ? ${'checkUnitType'.$i}->id : null;
                $model->parent_code = ($i==1)? trim($row[1]): $row[$unit_code-$step];
                $model->level = $i;
                $model->status = 1;
                $model->save();

                if (isset($row[$unit_manager])) {
                    $profiles_code = explode(',',$row[$unit_manager]);
                    foreach($profiles_code as $profile_code) {
                        $query = UnitManager::firstOrNew(['user_code' => $data[$i][$profile_code][1], 'unit_code' => trim($row[$unit_code])]);
                        $query->unit_code = trim($row[$unit_code]);
                        $query->unit_id = $model->id;
                        $query->user_code = $data[$i][$profile_code][1];
                        $query->user_id = $data[$i][$profile_code][0];
                        $query->type = 2;
                        $query->manager_type = 1;
                        $query->save();
                    }
                }
            }
            $unit_code +=$step; $unit_name+=$step; $unit_type+=$step; $unit_manager+=$step;
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
    //             $this->imported_by->notify(new ImportUnitHasFailed([$event->getException()->getMessage()]));
    //         },
    //     ];
    // }
}
