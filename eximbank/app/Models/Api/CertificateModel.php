<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateModel extends Model
{
    use HasFactory;
    protected $table = "el_certificate";
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'image',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
