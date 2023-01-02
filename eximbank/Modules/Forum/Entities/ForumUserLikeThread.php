<?php

namespace Modules\Forum\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ForumUserLikeThread extends Model
{
    use Cachable;
    protected $table = 'el_forum_user_like_thread';
    protected $table_name = 'HV like bài viết diễn đàn';
    protected $fillable = [
        'thread_id',
        'user_id',
        'like',
        'dislike',
    ];
    protected $primaryKey = 'id';

    public function thread()
    {
        return $this->belongsTo('Modules\Forum\Entities\ForumThread','thread_id');
    }
}
