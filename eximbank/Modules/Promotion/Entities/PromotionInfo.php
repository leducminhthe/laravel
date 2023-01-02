<?php

namespace Modules\Promotion\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class PromotionInfo extends Model
{
    use Cachable;
    protected $primaryKey = 'id';
    protected $table = 'el_info_promotion';
    protected $fillable = [
        'location',
        'time',
        'phone_number',
        'date_from',
        'date_to',
        'note',
    ];
}
