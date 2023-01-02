<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigModel extends Model
{
    use HasFactory;
    protected $table = 'el_config';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'value',
        'object',
    ];
}
