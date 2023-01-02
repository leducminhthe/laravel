<?php

namespace App\Models;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Analytics
 *
 * @property int $id
 * @property int $user_id
 * @property string $start_date
 * @property string|null $end_date
 * @property string|null $ip_address
 * @property string $day
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Analytics whereUserId($value)
 * @mixin \Eloquent
 */
class Analytics extends Model
{
    use Cachable;
    protected $table = 'el_analytics';
    protected $table_name = "Thống kê";
    protected $primaryKey = 'id';
}
