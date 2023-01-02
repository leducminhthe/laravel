<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardByUser extends BaseModel
{
    use Cachable;
    protected $table = 'el_dashboard_by_user';
    protected $table_name = "Thống kê của tôi";
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'condition',
        'created_by',
        'updated_by',
        'unit_by',
        'color',
        'i_text',
        'b_text',
        'images_web',
        'images_mobile',
        'location',
        'year',
    ];

}
