<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaModel extends Model
{
    use HasFactory;
    protected $table = 'el_area';
    protected $fillable =[
        'code',
        'name',
        'level',
        'parent_code',
        'status',
        'unit_id',
    ];
}
