<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideModel extends Model
{
    use HasFactory;
    protected $table = 'el_guide';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'attach',
        'type',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
