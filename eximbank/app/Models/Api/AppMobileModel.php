<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppMobileModel extends Model
{
    use HasFactory;
    protected $table = 'el_app_mobile';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'link',
        'type',
        'created_by',
        'updated_by',
    ];
}
