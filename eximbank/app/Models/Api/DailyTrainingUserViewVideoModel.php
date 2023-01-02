<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingUserViewVideoModel extends Model
{
    use HasFactory;
    protected $table = 'el_daily_training_user_view_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
        'device',
        'time_view',
    ];
}
