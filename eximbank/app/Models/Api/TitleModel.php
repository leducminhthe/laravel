<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleModel extends Model
{
    use HasFactory;
    protected $table = 'el_titles';
    protected $fillable = [
        'code',
        'name'
    ];
}
