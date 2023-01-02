<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class KpiTemplate extends BaseModel
{
    protected $table = 'el_kpi_template';
    protected $fillable = [
        'image',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];
    protected $primaryKey = 'id';
}
