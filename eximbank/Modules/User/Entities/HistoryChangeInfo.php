<?php

namespace Modules\User\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\User\Entities\ManagerLevel
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_manager_id
 * @property int $level
 * @property string $start_date
 * @property string|null $end_date
 * @property int $approve
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereApprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUserManagerId($value)
 * @mixin \Eloquent
 * @property string $key
 * @property string|null $value_old
 * @property string $value_new
 * @property int|null $approve_by
 * @property string|null $approve_time
 * @property string|null $note
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryChangeInfo whereApproveBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryChangeInfo whereApproveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryChangeInfo whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryChangeInfo whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryChangeInfo whereValueNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistoryChangeInfo whereValueOld($value)
 */
class HistoryChangeInfo extends Model
{
    use Cachable;
    protected $table = 'el_history_change_info';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'key',
        'value_old',
        'value_new',
        'status',
        'approve_by',
        'approve_time',
        'note'
    ];

    public static function getAttributeName() {
        return [
            'user_id' => trans('lamenu.user'),
            'key' => 'Trường thay đổi',
            'value_new' => 'Giá trị thay đổi',
        ];
    }
}
