<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class TimeExperienceNavigate extends Model
{
    protected $table = 'el_time_experience_navigate';
    protected $primaryKey = 'id';
    protected $fillable = [
        'time_start',
        'time_end',
        'experience_navigate_id',
    ];
}
