<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteractionHistoryClear extends Model
{
    use Cachable;
    protected $table = 'el_interaction_history_clear';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'date_clear',
    ];

    public static function getAttributeName() {
        return [
            'user_id' => trans("latraining.student"),
            'date_clear' => 'Ngày clear',
        ];
    }
}
