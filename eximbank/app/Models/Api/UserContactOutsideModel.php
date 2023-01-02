<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserContactOutsideModel extends Model
{
    use HasFactory;
    protected $table = 'el_user_contact';
    protected $primaryKey = 'id';
    protected $fillable = [
        'content',
        'title',
    ];
}
