<?php

namespace App\Models\Categories;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class TrainingTeacherCertificate extends BaseModel
{
    use Cachable;
    protected $table = 'el_training_teacher_certificate';
    protected $table_name = "Chứng chỉ Giảng viên";
    protected $fillable = [
        'training_teacher_id',
        'name_certificate',
        'name_school',
        'rank',
        'time_start',
        'date_license',
        'score',
        'result',
        'note',
        'certificate',
        'date_effective',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name_certificate' => 'Tên chứng chỉ',
            'name_school' => 'Tên trường',
            'result' => trans('latraining.result'),
            'certificate' => 'Chứng chỉ',
        ];
    }
}
