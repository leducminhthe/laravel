<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteractionHistory extends Model
{
    use Cachable;
    protected $table = 'el_interaction_history';
    protected $table_name = "Lịch sử tương tác";
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'code',
        'name',
        'number',
    ];

    public static function getAttributeName() {
        return [
            'user_id' => trans("latraining.student"),
            'code' => 'Mã',
            'name' => 'Tên',
            'number' => 'Số lần tương tác',
        ];
    }
}
