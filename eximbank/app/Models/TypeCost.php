<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class TypeCost extends Model
{
    // use Cachable;
    protected $table = 'el_type_cost';
    protected $table_name = "Loại chi phí";
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'type',
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên loại chi phí',
            'code' => 'Mã loại chi phí',
            'type' => trans('lacategory.form'),
        ];
    }
}
