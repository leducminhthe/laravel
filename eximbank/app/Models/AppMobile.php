<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Slider
 *
 * @property int $id
 * @property string $image
 * @property string|null $description
 * @property string $location
 * @property int $status
 * @property int $display_order
 * @property string|null $url
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Slider whereUrl($value)
 * @mixin \Eloquent
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereType($value)
 * @property string $link đường dẫn đến trang download app
 * @method static \Illuminate\Database\Eloquent\Builder|AppMobile whereLink($value)
 */
class AppMobile extends Model
{
    use Cachable;
    protected $table = 'el_app_mobile';
    protected $table_name = "App Mobile";
    protected $primaryKey = 'id';
    protected $fillable = [
        'image',
        'link',
        'file',
        'type',
        'created_by',
        'updated_by',
    ];

    public static function getAttributeName() {
        return [
            'image' => trans("latraining.picture"),
        ];
    }
}
