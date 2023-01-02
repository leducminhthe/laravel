<?php

namespace Modules\VirtualClassroom\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class VirtualClassroom extends BaseModel
{
    use Cachable;
    protected $table = 'el_virtual_classroom';
    protected $fillable = [
        'code',
        'name',
        'start_date',
        'end_date',
        'content',
        'status',
        'created_by',
        'updated_by',
        'unit_by'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã',
            'name' => 'Tên',
            'start_date' => trans('latraining.start_date'),
            'end_date' => trans('latraining.end_date'),
            'content' => trans("latraining.content"),
            'status' => trans("latraining.status"),
            'created_by' => trans('laother.creator'),
            'updated_by' => trans('laother.editor'),
        ];
    }
}
