<?php

namespace App\Models;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'el_contact';
    protected $table_name = "Liên hệ";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
    ];
    public static function getAttributeName() {
        return [
            'name' => trans('laother.contact_name'),
            'description' => trans("latraining.content"),
        ];
    }
}
