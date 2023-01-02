<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingPhotoModel extends Model
{
    use HasFactory;
    protected $table = 'el_advertising_photo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'status',
        'url',
        'type',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
