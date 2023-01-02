<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingUserLikeCommentVideoModel extends Model
{
    use HasFactory;
    protected $table = 'el_daily_training_user_like_comment_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
        'comment_id',
        'like',
        'dislike',
    ];
}
