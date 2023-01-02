<?php
namespace App\Imports;
use App\Models\Categories\TrainingProgram;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;
use App\Models\Categories\UnitType;
use App\Models\Categories\LevelSubject;

class LevelSubjectImport implements WithStartRow, ToModel
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
        if (!isset($row[1])) {
            $this->errors[] = 'Mã chủ đề Dòng '. $row[0] .': không được trống';
            $error = true;
        } elseif (isset($row[3])) {
            $checkTrainingProgram = TrainingProgram::where('code', $row[1])->first();
            if(empty($checkTrainingProgram)) {
                $this->errors[] = 'Chủ đề Dòng '. $row[0] .': không tồn tại';
                $error = true;
            }
        }

        if (!isset($row[2])) {
            $this->errors[] = 'Mã mảng nghiệp vụ Dòng '. $row[0] .': không được trống';
            $error = true;
        }

        if (!isset($row[3])) {
            $this->errors[] = 'Tên mảng nghiệp vụ Dòng '. $row[0] .': không được trống';
            $error = true;
        }

        if($error) {
            return null;
        }

        $model = LevelSubject::firstOrNew(['code' => trim($row[1])]);
        $model->training_program_id = $checkTrainingProgram->id;
        $model->code = trim($row[1]);
        $model->name = $row[2];
        $model->status = 1;
        $model->save();
    }

    public function startRow(): int
    {
        return 2;
    }
}
