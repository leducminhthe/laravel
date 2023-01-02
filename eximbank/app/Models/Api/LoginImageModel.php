<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginImageModel extends Model
{
    use HasFactory;
    protected $table = 'el_login_image';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'type',
        'status',
        'created_by',
        'updated_by',
    ];
}
