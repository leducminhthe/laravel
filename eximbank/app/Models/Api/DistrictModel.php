<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
    use HasFactory;
    protected $table = 'el_district';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'province_id',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function province(){
        return $this->hasOne(ProvinceModel::class, 'id', 'province_id');
    }
}
