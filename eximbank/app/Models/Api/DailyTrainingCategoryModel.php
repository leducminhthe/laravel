<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingCategoryModel extends Model
{
    use HasFactory;
    protected $table = 'el_daily_training_category';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'status_video',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
