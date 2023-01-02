<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterWordModel extends Model
{
    use HasFactory;
    protected $table = 'el_filter_words';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'status',
    ];
}
