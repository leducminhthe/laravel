<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTypeUser extends Model
{
    protected $table = 'el_subject_type_user';
    protected $primaryKey = 'id';
    protected $fillable = [
        'subject_type_id',
        'subject_id',
        'user_id',
    ];
}
