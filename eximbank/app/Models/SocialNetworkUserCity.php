<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserCity extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_city';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'city',
        'type',
    ];
}
