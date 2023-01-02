<?php

namespace Modules\Messages\Entities;

use App\Models\User;
use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Modules\BotConfig\Entities\BotConfigSuggest;


/**
 * Modules\Messages\Entities\Message
 *
 * @property int $id
 * @property string|null $room
 * @property string $message
 * @property int $from
 * @property int $to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    use Cachable;
    protected $table='messages';
    protected $fillable = ['room', 'message', 'from', 'to', 'seen', 'suggest_id'];
    protected $casts = [
        'from' => 'int',
        'to' => 'int',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function sender () {
        return $this->belongsTo(User::class, 'from');
    }

    public function receiver () {
        return $this->belongsTo(User::class, 'to');
    }

    public function suggest()
    {
        return $this->hasMany(BotConfigSuggest::class,'parent_id','suggest_id');
    }
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}
