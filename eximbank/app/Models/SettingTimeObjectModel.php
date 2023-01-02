<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SettingTimeObjectModel extends Model
{
    protected $table = 'el_setting_time_object';
    protected $primaryKey = 'id';
    protected $fillable = [
        'object',
    ];
}
