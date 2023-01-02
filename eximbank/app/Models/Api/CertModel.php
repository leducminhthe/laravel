<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertModel extends Model
{
    use HasFactory;
    protected $table = 'el_cert';
    protected $primaryKey = 'id';
    protected $fillable = [
        'certificate_code',
        'certificate_name',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];

}
