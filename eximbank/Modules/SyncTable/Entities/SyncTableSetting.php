<?php

namespace Modules\SyncTable\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\SyncTable\Entities\SyncTableSetting
 *
 * @property int $id
 * @property string $from_table table nguồn
 * @property string $to_table table đích
 * @property string $from_column cột table nguồn
 * @property string $to_column cột table đích
 * @property string $relationship cột quan hệ table đích
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting whereFromColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting whereFromTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting whereRelationship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting whereToColumn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting whereToTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyncTableSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SyncTableSetting extends Model
{
    protected $table = "sync_table_setting";
    protected $fillable = [];
}
