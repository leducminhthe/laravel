<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;
    protected $table = 'el_roles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'type',
        'guard_name',
        'description',
        'created_by',
        'updated_by',
        'unit_by',
    ];

}
