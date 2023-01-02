<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumCategoryModel extends Model
{
    use HasFactory;
    protected $table = 'el_forum_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'icon',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
        'unit_id',
    ];

    public function forum(){
        return $this->hasMany(ForumModel::class, 'category_id', 'id');
    }
}
