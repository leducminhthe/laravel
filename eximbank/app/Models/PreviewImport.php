<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class PreviewImport extends Model
{
    protected $table = 'el_preview_import';
    protected $fillable = [
        'name_import',
        'column1',
        'column2',
        'column3',
        'column4',
        'column5',
        'column6',
        'column7',
        'column8',
        'column9',
        'column10',
        'column11',
        'column12',
    ];
}
