<?php

namespace App\Models\Categories;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Categories\UnitTypeCode
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\UnitType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UnitTypeCode extends BaseModel
{
    use Cachable;
    protected $table = 'el_unit_type_code';
    protected $table_name = "Mã loại đơn vị";
    protected $fillable = [
        'code',
        'unit_type_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã',
        ];
    }
}
