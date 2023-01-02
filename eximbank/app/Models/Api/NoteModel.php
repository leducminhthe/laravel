<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteModel extends Model
{
    use HasFactory;
    protected $table = 'el_note';
    protected $primaryKey = 'id';
    protected $fillable = [
        'date_time',
        'content',
        'user_id',
        'type',
    ];
}
