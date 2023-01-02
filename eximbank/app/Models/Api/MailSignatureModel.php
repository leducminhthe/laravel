<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailSignatureModel extends Model
{
    use HasFactory;
    protected $table = 'el_mail_signature';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'content',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
