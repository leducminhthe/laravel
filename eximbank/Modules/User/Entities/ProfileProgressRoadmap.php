<?php

namespace Modules\User\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ProfileProgressRoadmap extends Model
{
    use Cachable;
    protected $table = 'el_profile_progress_roadmap';
    protected $fillable = [
        'user_id',
        'title_id',
        'percent',
    ];
}
