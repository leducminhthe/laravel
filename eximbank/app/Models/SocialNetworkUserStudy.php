<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserStudy extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_study';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'description',
        'status',
        'year_start',
        'year_end',
        'type',
        'type_study',
    ];
}
