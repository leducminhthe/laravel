<?php
namespace App\Imports;

use App\Models\Categories\Titles;
use App\Models\Categories\TitleRank;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;
use App\Models\Categories\UnitType;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Categories\Unit;

class TitlesImport implements WithStartRow, ToModel
{
    use Importable;
    public $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    public function model(array $row)
    {
        $error = false;
        $checkTitleRank = '';
        $checkUnitType = '';
        if (isset($row[5])) {
            $checkUnitType = UnitType::where('name','like','%'.trim($row[5]).'%')->first();
            if(!$checkUnitType) {
                $this->errors[] = 'Dòng '. $row[0] .': Loại đơn vị không đúng';
                $error = true;
            }
        }

        if (empty($row[3])){
            $this->errors[] = 'Dòng '. $row[0] .': Mã Cấp bậc không được trống';
            $error = true;
        }

        if (isset($row[3])) {
            $checkTitleRank = TitleRank::where('code', trim($row[3]))->first();
            if(empty($checkTitleRank)) {
                if (empty($row[4])){
                    $this->errors[] = 'Dòng '. $row[0] .': Tên Cấp bậc không được trống';
                    $error = true;
                }
                $saveTitleRank = new TitleRank();
                $saveTitleRank->status = 1;
                $saveTitleRank->code = $row[3];
                $saveTitleRank->name = trim($row[4]);
                $saveTitleRank->save();
            } 
        }

        if($error) {
            return null;
        }

        $model = Titles::firstOrNew(['code' => trim($row[1])]);

        // kiểm tra chức danh có thuộc đơn vị quản lý hay ko
        if(!Permission::isAdmin() && isset($model)){
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
                if(!in_array($model->unit_by, $getArray)) {
                    $this->errors[] = 'Dòng '. $row[0] .': Chức danh Không thuộc đơn vị quản lý: '. $user_role->name .'';
                    return null;
                }
            } else {
                if($model->unit_by != $user_role->unit_id) {
                    $this->errors[] = 'Dòng '. $row[0] .': Chức danh Không thuộc đơn vị quản lý: '. $user_role->name .'';
                    return null;
                }
            }
        }

        $model->code = trim($row[1]);
        $model->name = $row[2];
        $model->group = $checkTitleRank ? $checkTitleRank->id : $saveTitleRank->id;
        $model->unit_type = !empty($checkUnitType) ? $checkUnitType->id : null;
        $model->status = 1;
        $model->created_by = $model->created_by ? $model->created_by : profile()->user_id;
        $model->updated_by = profile()->user_id;
		$model->title_time_kpi = $row[6] ? $row[6] : null;
        $model->user_time_kpi = $row[7] ? $row[7] : null;
        $model->save();
    }

    public function startRow(): int
    {
        return 2;
    }
}
