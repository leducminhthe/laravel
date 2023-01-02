<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitMonthModel extends Model
{
    use HasFactory;
    protected $table = 'el_commitment';
    protected $primaryKey = 'id';
    protected $fillable = [
        'min_cost',
        'max_cost',
        'month',
        'group_id',
        'training_type_id',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
