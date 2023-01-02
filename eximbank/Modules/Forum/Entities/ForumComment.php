<?php

namespace Modules\Forum\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    use Cachable;
    protected $table = 'el_forum_comment';
    protected $table_name = 'Bình luận diễn đàn';
    protected $fillable = [
        'comment',
        'thread_id',
        'created_by',
        'created_at',
        'updated_at'
    ];
    protected $primaryKey = 'id';
    public static function getAttributeName() {
        return [
            'comment' => trans("latraining.comment"),
            'thread_id'=>'Danh mục con',
            'created_by'=>trans('laother.creator'),
            'created_at'=> trans("latraining.created_at"),
            'updated_at'=>trans('laother.editor')
            ];
    }
    public static function CountComment($comment=0){
        $query = self::query();
        $query->where('thread_id', '=', $comment);
        $count = $query->count();
        return $count;
    }

    public function thread()
    {
        return $this->belongsTo('Modules\Forum\Entities\ForumThread','thread_id');
    }
}
