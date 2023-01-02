<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfomationCompanyModel extends Model
{
    use HasFactory;
    protected $table = 'el_infomation_company';
    protected $primaryKey = 'id';
    protected $fillable = [
        'content',
        'title',
    ];
}
