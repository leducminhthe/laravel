<?php

namespace Modules\Coaching\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CoachingGroup extends BaseModel
{
    use Cachable;
    protected $table = "el_coaching_group";
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public static function getAttributeName() {
        return [
            'code' => 'Mã nhóm',
            'name' => 'Tên nhóm',
            'status' => trans("latraining.status"),
        ];
    }
}
