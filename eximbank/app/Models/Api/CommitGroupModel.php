<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitGroupModel extends Model
{
    use HasFactory;
    protected $table = 'el_commit_group';
    protected $primaryKey = 'id';
    protected $fillable = [
        'training_type_id',
        'group',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
