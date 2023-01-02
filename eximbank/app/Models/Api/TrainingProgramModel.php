<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgramModel extends Model
{
    use HasFactory;
    protected $table = 'el_training_program';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function subject(){
        return $this->hasMany(SubjectModel::class, 'training_program_id', 'id');
    }
}
