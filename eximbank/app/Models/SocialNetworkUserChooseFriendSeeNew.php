<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserChooseFriendSeeNew extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_choose_friend_see_new';
    protected $primaryKey = 'id';
    protected $fillable = [
        'friend_id',
        'social_network_id',
    ];
}
