<?php

namespace Modules\Capabilities\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CapabilitiesCategoryGroup extends Model
{
//    use Cachable;
    protected $table = 'el_capabilities_category_group';
    protected $fillable = [
        'name',
        'category_id'
    ];
    protected $primarykey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans('laother.category_name'),
            'category_id' => trans('labutton.parent_category')
        ];
    }
}
