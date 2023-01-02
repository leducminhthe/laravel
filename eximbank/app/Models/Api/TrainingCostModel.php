<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCostModel extends Model
{
    use HasFactory;
    protected $table = 'el_training_cost';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'type',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
