<?php

namespace Modules\DashboardUnit\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DashboardUnitCountOffline extends Model
{
    use Cachable;
    protected $table = 'el_dashboard_unit_count_offline';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'unit_code',
        'total',
        'year',
    ];
}
