<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumThreadModel extends Model
{
    use HasFactory;
    protected $table = 'el_forum_thread';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'content',
        'forum_id',
        'main_article',
        'status',
        'views',
        'total_comment',
        'created_by',
        'updated_by',
        'hashtag',
    ];

    public function forum()
    {
        return $this->hasOne(ForumModel::class, 'id','forum_id');
    }

    public function forum_comment()
    {
        return $this->hasMany(ForumCommentModel::class,'thread_id', 'id');
    }
}
