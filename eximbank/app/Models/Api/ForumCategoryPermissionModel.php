<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCategoryPermissionModel extends Model
{
    use HasFactory;
    protected $table = 'el_forum_category_permission';
    protected $primaryKey = 'id';
    protected $fillable = [
        'forum_cate_id',
        'unit_id',
        'user_id'
    ];
}
