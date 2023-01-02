<?php

namespace Modules\Config\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Config\Entities\ConfigEmail
 *
 * @property int $id
 * @property string|null $driver
 * @property string|null $host
 * @property int|null $port
 * @property string|null $user
 * @property string|null $password
 * @property string|null $encryption
 * @property string|null $from_name
 * @property string|null $address
 * @property int|null $company
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereEncryption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereFromName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfigEmail whereUser($value)
 * @mixin \Eloquent
 */
class ConfigEmail extends Model
{
    protected $table = 'config_email';
    protected $table_name = 'Thiết lập gửi mail';
    protected $fillable = [
        'driver',
        'host',
        'port',
        'user',
        'password',
        'encryption',
        'from_name',
        'address',
        'company',
        'send_noty',
    ];
}
