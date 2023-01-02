<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitTypeCodeModel extends Model
{
    use HasFactory;
    protected $table = 'el_unit_type_code';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'unit_type_id',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function unit_type(){
        return $this->hasOne(UnitTypeModel::class, 'id', 'unit_type_id');
    }
}
