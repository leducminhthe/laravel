<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingSettingScoreLikeModel extends Model
{
    use HasFactory;
    protected $table = 'el_daily_training_setting_score_like';
    protected $primaryKey = 'id';
    protected $fillable = [
        'from',
        'to',
        'score',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
