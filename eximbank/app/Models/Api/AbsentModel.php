<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsentModel extends Model
{
    use HasFactory;
    protected $table = "el_absent";
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
