<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostLessonsModel extends Model
{
    use HasFactory;
    protected $table = 'el_cost_lessons';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'cost',
        'status',
    ];

}
