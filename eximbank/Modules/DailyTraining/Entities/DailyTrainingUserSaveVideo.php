<?php

namespace Modules\DailyTraining\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingUserSaveVideo extends Model
{
    use Cachable;
    protected $table = 'el_daily_user_save_video';
    protected $table_name = 'HV đánh dấu video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
    ];
}
