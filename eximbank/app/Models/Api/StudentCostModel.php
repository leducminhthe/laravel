<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCostModel extends Model
{
    use HasFactory;
    protected $table = 'el_student_cost';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
