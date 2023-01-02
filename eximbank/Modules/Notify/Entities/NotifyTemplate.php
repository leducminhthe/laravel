<?php

namespace Modules\Notify\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class NotifyTemplate extends BaseModel
{
    use Cachable;
    protected $table = 'el_notify_template';
    protected $table_name = 'Mẫu thông báo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'title',
        'content',
        'note',
        'status'
    ];
}
