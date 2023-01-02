<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineFinishVideo extends Model
{
    protected $table = 'offline_finish_activity_video';
    protected $fillable = [
        'course_id',
        'user_id',
        'video_id',
    ];
    protected $primaryKey = 'id';
}
