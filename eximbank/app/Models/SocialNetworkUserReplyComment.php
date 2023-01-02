<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserReplyComment extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_reply_comment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'comment_id',
        'reply',
        'user_name',
        'avatar',
    ];
}
