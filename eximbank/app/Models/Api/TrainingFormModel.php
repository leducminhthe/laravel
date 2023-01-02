<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingFormModel extends Model
{
    use HasFactory;
    protected $table = 'el_training_form';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'training_type_id',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
