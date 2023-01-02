<?php

namespace Modules\Capabilities\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CapabilitiesCategory extends Model
{
//    use Cachable;
    protected $table = 'el_capabilities_category';
    protected $fillable = [
        'name',
    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans('laother.category_name'),
        ];
    }

}
