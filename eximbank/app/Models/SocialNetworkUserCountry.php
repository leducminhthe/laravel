<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserCountry extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_country';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'country',
        'type',
    ];
}
