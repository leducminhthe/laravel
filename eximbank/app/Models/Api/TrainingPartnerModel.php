<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingPartnerModel extends Model
{
    use HasFactory;
    protected $table = 'el_training_partner';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'people',
        'address',
        'email',
        'phone',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
