<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingVideoModel extends Model
{
    use HasFactory;
    protected $table = 'el_daily_training_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'video',
        'avatar',
        'hashtag',
        'category_id',
        'created_by',
        'updated_by',
        'unit_by',
        'user_approve',
        'time_approve',
        'view',
        'status',
        'approve',
    ];
}
