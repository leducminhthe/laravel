<?php

namespace Modules\Quiz\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class QuizTimeFinishPoint extends Model
{
    protected $table = 'quiz_time_finish_point';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quiz_id',
        'userpoint_setting_id',
        'rank',
        'score',

    ];
}
