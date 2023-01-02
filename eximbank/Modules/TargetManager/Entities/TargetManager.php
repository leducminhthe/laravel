<?php

namespace Modules\TargetManager\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class TargetManager extends BaseModel
{
    protected $table = 'el_target_manager';
    protected $fillable = [
        'name',
        'parent_id',
        'group_object',
        'num_hour_student',
        'num_course_student',
        'num_hour_teacher',
        'num_course_teacher',
        'type',
        'created_by',
        'updated_by',
        'unit_by',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans("lacategory.name"),
        ];
    }
}
