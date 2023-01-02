<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserAddFriend extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_add_firend';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];
}
