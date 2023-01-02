<?php

namespace Modules\VirtualClassroom\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class VirtualClassroomTeacher extends Model
{
    use Cachable;
    protected $table = 'el_virtual_classroom_teacher';
    protected $fillable = [
        'virtual_classroom_id',
        'teacher_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'virtual_classroom_id' => trans('latraining.online'),
            'teacher_id' => trans('lareport.teacher'),
        ];
    }
}
