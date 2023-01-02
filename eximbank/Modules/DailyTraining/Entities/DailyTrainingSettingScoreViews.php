<?php

namespace Modules\DailyTraining\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingSettingScoreViews extends BaseModel
{
    use Cachable;
    protected $table = 'el_daily_training_setting_score_views';
    protected $table_name = 'Điểm thưởng khi xem video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'category_id',
        'from',
        'to',
        'score',
    ];

    public static function getAttributeName() {
        return [
            'from' => 'Từ',
            'to' => 'Đến',
            'score' => 'Điểm',
        ];
    }
}
