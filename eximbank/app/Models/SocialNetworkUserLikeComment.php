<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SocialNetworkUserLikeComment extends Model
{
    use Cachable;
    protected $table = 'el_social_network_user_like_comment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'type',
        'comment_id',
    ];
}
