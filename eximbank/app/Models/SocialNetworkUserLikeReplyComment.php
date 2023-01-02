<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserLikeReplyComment extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_like_reply_comment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'type',
        'reply_id',
    ];
}
