<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingTimeModel extends Model
{
    use HasFactory;
    protected $table = 'el_setting_time';
    protected $primaryKey = 'id';
    protected $fillable = [
        'start_time',
        'end_time',
        'session',
        'object',
        'value',
    ];
}
