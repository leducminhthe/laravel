<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserComment extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_comment_new';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'social_network_new_id',
        'comment',
        'avatar',
        'user_name',
        'total_reply',
    ];
}
