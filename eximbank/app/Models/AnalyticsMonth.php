<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AnalyticsMonth
 *
 * @property int $id
 * @property int $user_id
 * @property string $month
 * @property int $access
 * @property float $minute
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth whereAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth whereMinute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AnalyticsMonth whereUserId($value)
 * @mixin \Eloquent
 */
class AnalyticsMonth extends Model
{
    use Cachable;
    protected $table = 'el_analytics_month';
    protected $table_name = "Thống kê tháng";
    protected $primaryKey = 'id';
    protected $fillable = [
        'access',
        'minute'
    ];
}
