<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteractionHistoryName extends Model
{
    use Cachable;
    protected $table = 'el_interaction_history_name';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
    ];

    public static function getAttributeName() {
        return [
            'code' => 'Mã',
            'name' => 'Tên',
        ];
    }
}
