<?php

namespace Modules\DailyTraining\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Type;

class DailyTrainingUserLikeCommentVideo extends Model
{
    use Cachable;
    protected $table = 'el_daily_training_user_like_comment_video';
    protected $table_name = 'HV like bình luận trong video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'video_id',
        'user_id',
        'comment_id',
        'like',
        'dislike',
    ];

    public static function getAttributeName() {
        return [
            'video_id' => 'Video',
            'user_id' => 'Người like',
            'like' => 'like',
            'dislike' => 'dislike',
            'comment_id' => trans("latraining.comment"),
        ];
    }

    public static function countLikeOrDisLike($video, $comment, $type)
    {
        if ($type == 1){
            $count_like_comment = DailyTrainingUserLikeCommentVideo::query()
                ->where('comment_id', '=', $comment)
                ->where('video_id', '=', $video)
                ->where('like', '=', 1)
                ->count();

            return $count_like_comment;
        }else{
            $count_dislike_comment = DailyTrainingUserLikeCommentVideo::query()
                ->where('comment_id', '=', $comment)
                ->where('video_id', '=', $video)
                ->where('dislike', '=', 1)
                ->count();

            return $count_dislike_comment;
        }
    }
}
