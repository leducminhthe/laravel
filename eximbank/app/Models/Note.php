<?php

namespace App\Models;

use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use http\Client\Request;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class Note extends Model
{
    use Cachable;
    protected $table = 'el_note';
    protected $table_name = "Ghi chú";
    protected $fillable = [
        'date_time',
        'content',
        'user_id',
        'type',
    ];
}
