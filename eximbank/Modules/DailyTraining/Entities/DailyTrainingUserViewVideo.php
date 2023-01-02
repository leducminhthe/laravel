<?php

namespace Modules\DailyTraining\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingUserViewVideo extends Model
{
    use Cachable;
    protected $table = 'el_daily_training_user_view_video';
    protected $table_name = 'HV xem video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
        'device',
        'time_view',
    ];

    public static function getAttributeName() {
        return [
            'video_id' => 'Video',
            'user_id' => 'Người xem',
            'device' => 'Thiết bị xem',
            'time_view' => 'Thời gian bắt đầu xem',
        ];
    }
}
