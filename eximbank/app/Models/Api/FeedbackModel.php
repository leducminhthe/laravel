<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackModel extends Model
{
    use HasFactory;
    protected $table = 'el_feedback';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'image',
        'position',
        'star',
        'content',
        'created_by',
        'updated_by',
    ];
}
