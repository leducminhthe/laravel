<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumModel extends Model
{
    use HasFactory;
    protected $table = 'el_forum';
    protected $primaryKey = 'id';
    protected $fillable = [
        'icon',
        'name',
        'status',
        'category_id',
        'num_topic',
        'num_comment',
        'created_by',
        'updated_by',
    ];

    public function forum_category(){
        return $this->hasOne(ForumCategoryModel::class, 'id', 'category_id');
    }

    public function forum_thread(){
        return $this->hasMany(ForumThreadModel::class, 'forum_id', 'id');
    }
}
