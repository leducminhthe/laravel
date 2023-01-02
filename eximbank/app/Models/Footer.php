<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Footer
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $link_youtobe
 * @property string|null $link_fb
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer whereLinkFb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer whereLinkYoutobe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Footer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Footer extends Model
{
    protected $table = 'el_footer';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'link_youtobe',
        'link_fb',
        'status',
    ];
}
