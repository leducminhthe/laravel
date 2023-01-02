<?php

namespace Modules\Notify\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RemoveNotifySend extends Model
{
    use Cachable;
    protected $table = 'el_remove_notify_send';
    protected $primaryKey = 'id';
    protected $fillable = [];
}
