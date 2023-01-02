<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class UserContactOutside extends Model
{
    // use Cachable;
    protected $table = 'el_user_contact';
    protected $primaryKey = 'id';
    protected $fillable = [
        'content',
        'title',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'content' => trans("latraining.content"),
            'title' => 'Tiêu đề',
        ];
    }
}
