<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    use HasFactory;
    protected $table = 'el_subject';
    protected $fillable =[
        'code',
        'name',
        'level_subject_id',
        'training_program_id',
        'created_date',
        'created_by',
        'unit_id',
        'condition',
        'status',
        'description',
        'content',
    ];
}
