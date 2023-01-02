<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitName extends Model
{
    // use Cachable;
    protected $table = 'el_unit_name';
    protected $fillable=[
        'level',
        'name',
        'name_en',
        'description',
    ];

    public static function getAttributeName() {
        return [
            'level' => trans('laother.levels'),
            'name' => trans('lacategory.unit'),
            'name_en' => trans('lacategory.unit'). '(EN)',
        ];
    }
}
