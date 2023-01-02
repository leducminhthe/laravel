<?php

namespace Modules\SyncTable\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\SyncTable\Entities\SyncTable
 *
 * @property int $id
 * @property int $sync_table_setting_id
 * @property int $record_change
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTable query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTable whereRecordChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTable whereSyncTableSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTable whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SyncTable extends Model
{
    protected $table = "sync_table";
    protected $fillable = [
        'sync_table_setting_id',
        'record_change'
    ];

}
