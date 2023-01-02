<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Notifications\DatabaseNotification;

/**
 * App\Models\Notifications
 *
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $notifiable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Notifications whereUpdatedAt($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] all($columns = ['*'])
 * @method static \Illuminate\Notifications\DatabaseNotificationCollection|static[] get($columns = ['*'])
 */
class Notifications extends DatabaseNotification
{
    use Cachable;
    protected $table = 'el_notifications';

    public function getMessages() {

    }
}
