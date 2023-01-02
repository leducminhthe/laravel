<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterModel extends Model
{
    use HasFactory;
    protected $table = 'el_footer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'link_youtobe',
        'link_fb',
        'status',
    ];
}
