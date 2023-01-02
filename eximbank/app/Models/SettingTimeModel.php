<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SettingTimeModel
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
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereUrl($value)
 * @mixin \Eloquent
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|SettingTimeModel whereType($value)
 */
class SettingTimeModel extends Model
{
    protected $table = 'el_setting_time';
    protected $table_name = "Cài đặt thời gian";
    protected $primaryKey = 'id';
    protected $fillable = [
        'start_time',
        'end_time',
        'session',
        'object',
        'i_text',
        'b_text',
        'color_text',
    ];
}
