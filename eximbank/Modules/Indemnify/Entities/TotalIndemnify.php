<?php

namespace Modules\Indemnify\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class TotalIndemnify extends Model
{
    use Cachable;
    protected $table = 'el_total_indemnify';
    protected $table_name = 'Tổng chi phí cam kết bồi hoàn';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'total_indemnify',
        'percent',
        'exemption_amount',
        'total_cost',
    ];
}
