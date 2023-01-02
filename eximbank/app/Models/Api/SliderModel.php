<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderModel extends Model
{
    use HasFactory;
    protected $table = 'el_slider';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'description',
        'location',
        'object',
        'status',
        'display_order',
        'type',
        'url',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
