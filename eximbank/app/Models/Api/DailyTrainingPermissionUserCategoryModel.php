<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingPermissionUserCategoryModel extends Model
{
    use HasFactory;
    protected $table = 'el_daily_training_permission_user_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'category_id',
        'user_id',
    ];
}
