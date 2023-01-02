<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderOutsideModel extends Model
{
    use HasFactory;
    protected $table = 'el_slider_outside';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'status',
        'url',
        'created_by',
        'updated_by',
    ];
}
