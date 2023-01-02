<?php

namespace Modules\DailyTraining\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DailyTrainingHistoryScore extends Model
{
    use Cachable;
    protected $table = 'el_daily_training_history_score';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'video_id',
        'score',
    ];
}
