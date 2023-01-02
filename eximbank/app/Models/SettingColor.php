<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SettingColor extends Model
{
    // use Cachable;
    protected $table = 'el_setting_color';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'text',
        'hover_text',
        'active',
        'background',
        'hover_background',
        'background_child',
    ];
}
