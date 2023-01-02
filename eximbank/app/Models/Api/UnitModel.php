<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitModel extends Model
{
    use HasFactory;
    protected $table = 'el_unit';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'level',
        'parent_code',
        'status',
        'type',
        'email',
        'note1',
        'note2',
        'area_id',
    ];
}
