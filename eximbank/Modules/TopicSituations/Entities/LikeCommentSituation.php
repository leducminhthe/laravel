<?php

namespace Modules\TopicSituations\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class LikeCommentSituation extends Model
{
    use Cachable;
    protected $table = 'el_like_comment_situation';
    protected $table_name = 'Like bình luận chuyên đề tình huống';
    protected $fillable = [
        'user_id',
        'comment_id',
        'reply_comment_id',
    ];
    protected $primaryKey = 'id';
}
