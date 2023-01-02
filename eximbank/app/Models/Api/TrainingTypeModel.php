<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTypeModel extends Model
{
    use HasFactory;
    protected $table="el_training_type";
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
    ];
}
