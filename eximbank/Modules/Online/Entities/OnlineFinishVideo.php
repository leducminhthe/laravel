<?php

namespace Modules\Online\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OnlineFinishVideo extends Model
{
    use Cachable;
    protected $table = 'el_finish_activity_video';
    protected $fillable = [
        'course_id',
        'user_id',
        'video_id',
    ];
    protected $primaryKey = 'id';
}
