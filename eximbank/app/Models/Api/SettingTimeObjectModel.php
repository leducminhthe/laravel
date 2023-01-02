<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingTimeObjectModel extends Model
{
    use HasFactory;
    protected $table = 'el_setting_time_object';
    protected $primaryKey = 'id';
    protected $fillable = [
        'object',
        'languages',
    ];
}
