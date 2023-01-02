<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitTypeModel extends Model
{
    use HasFactory;
    protected $table = 'el_unit_type';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function unit_type_code(){
        return $this->hasMany(UnitTypeCodeModel::class, 'unit_type_id', 'id');
    }
}
