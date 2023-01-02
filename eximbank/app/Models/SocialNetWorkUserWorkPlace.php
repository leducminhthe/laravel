<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetWorkUserWorkPlace extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_work_place';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'company',
        'position',
        'city',
        'description',
        'status',
        'year_start',
        'year_end',
        'type',
    ];
}
