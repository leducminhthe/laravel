<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class TotalTimeUserLearnInYear extends Model
{
    // use Cachable;
    protected $table = 'el_total_time_user_learn_year';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'total_time',
        'full_name',
        'unit_id',
        'unit_name',
        'title_id',
        'title_name',
        'year',
    ];
}
