<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCommentModel extends Model
{
    use HasFactory;
    protected $table = 'el_forum_comment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'comment',
        'thread_id',
        'created_by',
    ];

    public function forum_thread()
    {
        return $this->hasOne(ForumThreadModel::class, 'id','thread_id');
    }
}
