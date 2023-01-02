<?php

namespace Modules\Forum\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ForumSettingScoreComment extends Model
{
    use Cachable;
    protected $table = 'el_forum_setting_score_comment';
    protected $table_name = 'Điểm thưởng đạt mốc bình luận';
    protected $fillable = [
        'forum_id',
        'score',
        'reward_comment',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'reward_comment' => 'Mốc điểm',
            'score' => 'Điểm',
        ];
    }
}
