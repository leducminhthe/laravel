<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class Websockets extends Model
{
    protected $table = 'websockets_statistics_entries';
    protected $primaryKey = 'id';
    protected $fillable = [
        'app_id',
        'peak_connection_count',
        'websocket_message_count',
        'api_message_count',
    ];
}
