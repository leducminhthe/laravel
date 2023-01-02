<?php

namespace App\Models\Api;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoModel extends Model
{
    use Cachable;
    protected $table = 'el_logo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'object',
        'status',
        'type',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
