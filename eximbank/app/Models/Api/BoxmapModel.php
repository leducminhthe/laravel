<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxmapModel extends Model
{
    use HasFactory;
    protected $table = 'el_boxmaps';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'description',
        'lng',
        'lat'
    ];
}
