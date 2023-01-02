<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkGroupChat extends Model
{
    use Cachable;
    protected $table = 'el_social_network_group_chat';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_1',
        'user_2',
    ];
}
