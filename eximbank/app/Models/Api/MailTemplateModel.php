<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplateModel extends Model
{
    use HasFactory;
    protected $table = 'el_mail_template';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'title',
        'content',
        'note',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];
}
