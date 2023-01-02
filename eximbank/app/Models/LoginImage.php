<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class LoginImage extends Model
{
    // use Cachable;
    protected $table = 'el_login_image';
    protected $table_name = "Hình nền đăng nhập";
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'status',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'image' => trans("latraining.picture"),
        ];
    }
}
