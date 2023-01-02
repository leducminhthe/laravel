<?php

namespace Modules\SalesKit\Entities;

use Illuminate\Database\Eloquent\Model;

class SalesKitObject extends Model
{
    protected $table = 'el_sales_kit_object';
    protected $fillable = [
        'saleskit_id',
        'status',
        'unit_id',
        'title_id',
        'user_id',
    ];
    protected $primaryKey = 'id';
}
