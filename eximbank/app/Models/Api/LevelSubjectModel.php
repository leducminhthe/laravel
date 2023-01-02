<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelSubjectModel extends Model
{
    use HasFactory;
    protected $table = 'el_level_subject';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'status',
        'training_program_id',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
