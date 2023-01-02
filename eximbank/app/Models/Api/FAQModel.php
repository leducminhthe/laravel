<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQModel extends Model
{
    use HasFactory;
    protected $table = 'el_faq';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'content',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
