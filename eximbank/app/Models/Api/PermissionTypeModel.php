<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionTypeModel extends Model
{
    use HasFactory;
    protected $table = 'el_permission_type';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type',
        'description',
        'sort',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
