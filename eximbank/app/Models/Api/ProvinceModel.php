<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvinceModel extends Model
{
    use HasFactory;
    protected $table = 'el_province';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function district(){
        return $this->hasMany(DistrictModel::class, 'province_id', 'id');
    }
}
