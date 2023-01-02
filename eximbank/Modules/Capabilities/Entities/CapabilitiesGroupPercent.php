<?php

namespace Modules\Capabilities\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CapabilitiesGroupPercent extends Model
{
//    use Cachable;
    protected $table = 'el_capabilities_group_percent';
    protected $fillable = [
        'to_percent',
        'from_percent',
        'percent_group',

    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'to_percent' => 'Đến phần trăm',
            'from_percent' => 'Từ phần trăm',
            'percent_group' => 'Nhóm phần trăm',
        ];
    }
}
