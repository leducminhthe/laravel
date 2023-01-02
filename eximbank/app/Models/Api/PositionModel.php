<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionModel extends Model
{
    use HasFactory;
    protected $table = 'el_position';
    protected $fillable = [
        'code',
        'name'
    ];
}
