<?php

namespace Modules\TargetManager\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class TargetManagerParent extends BaseModel
{
    protected $table = 'el_target_manager_parent';
    protected $fillable = [
        'name',
        'year',
        'created_by',
        'updated_by',
        'unit_by',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans("latraining.name_target"),
            'year' => trans("lanote.year"),
        ];
    }

    public function child(){
        return $this->hasMany(TargetManager::class, 'parent_id', 'id');
    }
}
