<?php

namespace Modules\Forum\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class FilterWord extends Model
{
    use Cachable;
    protected $table = 'el_filter_words';
    protected $table_name = 'Lọc từ diễn đàn';
    protected $fillable = [
        'name',
        'status',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Chữ',
            'status'=> trans("latraining.status"),
        ];
    }
}
