<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    use HasFactory;
    protected $table = 'el_contact';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];
}
