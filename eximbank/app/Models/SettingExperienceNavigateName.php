<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SettingExperienceNavigateName extends Model
{
    protected $table = 'el_setting_experience_navigate_name';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'url',
    ];
}
