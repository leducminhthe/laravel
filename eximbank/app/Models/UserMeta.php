<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\UserMeta
 *
 * @property int $user_id
 * @property string $key
 * @property string|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserMeta whereValue($value)
 * @mixin \Eloquent
 */
class UserMeta extends Model
{
    use Cachable;
    protected $table = 'user_meta';
    protected $fillable = [
        'user_id',
        'key',
        'value'
    ];
    public $incrementing = false;
    public $timestamps = false;
}
