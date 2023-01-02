<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingTeacherModel extends Model
{
    use HasFactory;
    protected $table = 'el_training_teacher';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'teacher_type_id',
        'training_partner_id',
        'code',
        'name',
        'email',
        'phone',
        'status',
        'type',
        'account_number',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
