<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class MailSignature extends BaseModel
{
    protected $table = 'el_mail_signature';
    protected $table_name = "Chữ ký mail";
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_id',
        'content',
    ];
}
