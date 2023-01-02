<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SliderOutside extends Model
{
    // use Cachable;
    protected $table = 'el_slider_outside';
    protected $table_name = "Banner bên ngoài";
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'status',
        'url',
        'type',
        'created_by',
        'updated_by',
    ];
}
