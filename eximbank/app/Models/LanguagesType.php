<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class LanguagesType extends Model
{
    protected $table="el_languages_type";
    protected $primaryKey = 'id';
    protected $fillable = [
        'icon',
        'key',
        'name',
    ];

    public static function getAttributeName() {
        return [
            'icon' => 'Icon',
            'key' => 'Mã ngôn ngữ',
            'name' => 'Ngôn ngữ',
        ];
    }
}
