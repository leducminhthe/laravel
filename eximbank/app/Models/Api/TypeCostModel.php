<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeCostModel extends Model
{
    use HasFactory;
    protected $table = 'el_type_cost';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
    ];
}
