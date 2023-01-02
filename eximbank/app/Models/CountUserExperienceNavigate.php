<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CountUserExperienceNavigate extends Model
{
    use Cachable;
    protected $table = 'el_count_user_experience_navigate';
    protected $primaryKey = 'id';
    protected $fillable = [
        'experience_navigate_id',
        'user_id',
        'number_count',
    ];
}
