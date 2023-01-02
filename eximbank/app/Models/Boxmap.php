<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class Boxmap extends Model
{
    protected $table = 'el_boxmaps';
    protected $table_name = "Maps";
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'description',
        'lng',
        'lat',
        'note',
    ];
    public static function getAttributeName() {
        return [
            'title' => 'Tên',
            'description' => trans("latraining.content"),
            'lng' => 'lng',
            'lat' => 'lat',
        ];
    }
}
