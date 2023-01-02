<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SettingTimeValueLanguages extends Model
{
    protected $table = 'el_setting_time_value_languages';
    protected $primaryKey = 'id';
    protected $fillable = [
        'setting_time_id',
        'value',
        'languages',
    ];
}
