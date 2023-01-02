<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SettingExperienceNavigate extends Model
{
    // use Cachable;
    protected $table = 'el_setting_experience_navigate';
    protected $table_name = "Điều hướng trải nghiệm";
    protected $primaryKey = 'id';
    protected $fillable = [
        'start_date',
        'end_date',
        'total_count',
        'date_count',
    ];
}
