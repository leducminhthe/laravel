<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingLocationModel extends Model
{
    use HasFactory;
    protected $table = 'el_training_location';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'status',
        'province_id',
        'district_id',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}