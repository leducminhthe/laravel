<?php

namespace Modules\TopicSituations\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class CommentSituation extends Model
{
    use Cachable;
    protected $table = 'el_comment_situation';
    protected $table_name = 'Bình luận chuyên đề tình huống';
    protected $fillable = [
        'user_id',
        'topic_id',
        'comment',
        'situation_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'comment' => trans("latraining.comment"),
        ];
    }
}
