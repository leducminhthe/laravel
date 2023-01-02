<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class TotalTimeHistoryUser extends Model
{
    protected $table = 'el_total_time_history_user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'title_id',
        'time_second',
        'year',
    ];
}
