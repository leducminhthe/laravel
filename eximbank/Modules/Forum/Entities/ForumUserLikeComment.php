<?php

namespace Modules\Forum\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Type;

class ForumUserLikeComment extends Model
{
    use Cachable;
    protected $table = 'el_forum_user_like_comment';
    protected $table_name = 'HV like bình luận trong bài viết diễn đàn';
    protected $primaryKey = 'id';
    protected $fillable = [
        'thread_id',
        'user_id',
        'comment_id',
        'like',
        'dislike',
    ];

    public static function getAttributeName() {
        return [
            'thread_id' => trans("lamenu.post"),
            'user_id' => 'Người like',
            'like' => 'like',
            'dislike' => 'dislike',
            'comment_id' => trans("latraining.comment"),
        ];
    }

    public static function checkLikeComment($thread_id, $comment, $type){
        $query = ForumUserLikeComment::query();
        $query->where('thread_id', '=', $thread_id);
        $query->where('comment_id', '=', $comment);
        $query->where('user_id', '=', profile()->user_id);
        if ($type == 1){
            $query->where('like', '=', 1);
        }else{
            $query->where('dislike', '=', 1);
        }

        return $query->exists();
    }

    public static function countLikeOrDisLike($thread_id, $comment, $type)
    {
        if ($type == 1){
            $count_like_comment = ForumUserLikeComment::query()
                ->where('comment_id', '=', $comment)
                ->where('thread_id', '=', $thread_id)
                ->where('like', '=', 1)
                ->count();

            return $count_like_comment;
        }else{
            $count_dislike_comment = ForumUserLikeComment::query()
                ->where('comment_id', '=', $comment)
                ->where('thread_id', '=', $thread_id)
                ->where('dislike', '=', 1)
                ->count();

            return $count_dislike_comment;
        }
    }
}
