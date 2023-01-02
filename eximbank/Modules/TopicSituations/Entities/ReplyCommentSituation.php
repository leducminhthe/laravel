<?php

namespace Modules\TopicSituations\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class ReplyCommentSituation extends Model
{
    use Cachable;
    protected $table = 'el_reply_comment_situation';
    protected $table_name = 'Phản hồi bình luận chuyên đề tình huống';
    protected $fillable = [
        'user_id',
        'comment',
        'comment_id',
        'like',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'comment' => trans("latraining.comment"),
        ];
    }
}
