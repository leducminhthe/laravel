<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserStory extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_story';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'story',
    ];
}
