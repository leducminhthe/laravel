<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SliderPosition
 *
 * @property int $id
 * @property string $value
 * @property int $slider_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SliderPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SliderPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SliderPosition query()
 * @method static \Illuminate\Database\Eloquent\Builder|SliderPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SliderPosition whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SliderPosition whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SliderPosition whereSliderId($value)
 * @mixin \Eloquent
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|SliderPosition whereType($value)
 */
class SliderPosition extends Model
{
    protected $table = 'el_position_slider';
    protected $table_name = "Vị trí banner";
    protected $primaryKey = 'id';
    protected $fillable = [
        'value',
        'slider_id',
    ];
}
