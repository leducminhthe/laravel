<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumUserLikeCommentModel extends Model
{
    use HasFactory;
    protected $table = 'el_forum_user_like_comment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'thread_id',
        'user_id',
        'comment_id',
        'like',
        'dislike',
    ];
}
