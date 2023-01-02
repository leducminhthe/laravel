<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class LogoModel extends BaseModel
{
    // use Cachable;
    protected $table = 'el_logo';
    protected $table_name = "Logo";
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'status',
    ];

    public static function getAttributeName() {
        return [
            'image' => trans("latraining.picture"),
        ];
    }
}
