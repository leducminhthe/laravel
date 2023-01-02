<?php

namespace Modules\Report\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
class HistoryExport extends Model
{
    use Cachable;
    protected $table = 'el_history_export';
    protected $fillable = [
        'report_name',
        'file_name',
        'error',
        'status',
    ];

    public function user() {
        return $this->hasOne('App\Models\Profile', 'user_id', 'user_id');
    }
}
