<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingUserLikeVideoModel extends Model
{
    use HasFactory;
    protected $table = 'el_daily_training_user_like_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
        'like',
        'dislike',
    ];
}
