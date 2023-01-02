<?php

namespace Modules\Offline\Entities;

use App\Models\BaseModel;
use App\Models\Categories\UnitManager;
use App\Models\Permission;
use App\Models\Profile;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineRegister
 *
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property int|null $class_id
 * @property int $status
 * @property string|null $note
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $unit_by
 * @property int|null $cron_complete 1 đã chạy cron complete, 0 chưa chạy, null không chạy
 * @property string|null $approved_step
 * @property int $register_form
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Profile|null $user
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereApprovedStep($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereClassId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereCourseId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereCreatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereCreatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereCronComplete($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereNote($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereRegisterForm($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereStatus($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereUnitBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereUpdatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereUpdatedBy($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister whereUserId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineRegister withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
class OfflineRegister extends BaseModel
{
    use ChangeLogs, Cachable;
    protected $table = 'el_offline_register';
    protected $table_name = 'Ghi danh Khóa học tập trung';
    protected $fillable = [
        'user_id',
        'course_id',
        'class_id',
        'status',
        'cron_complete',
        'approved_step',
        'register_form',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'user_id' => trans('lamenu.user'),
            'course_id' => trans('lamenu.course'),
            'status' => trans("latraining.status"),
        ];
    }

    public static function checkExists($user_id,  $course_id, $claass_id, $status = null){
        $query = self::query();
        $query->where('user_id', '=', $user_id);
        $query->where('class_id', '=', $claass_id);
        $query->where('course_id', '=', $course_id);
        if (!is_null($status)) {
            $query->where('status', '=', $status);
        }
        return $query->exists();
    }

    public static function getUserRegister($registerId)
    {
        $query = self::query();
//        $query->where('id'=>)?
    }

    public static function countRegisters($course_id)
    {
        $managers =  UnitManager::getIdUnitManagedByUser();

        $query = OfflineRegister::query()
            ->from('el_offline_register AS register')
            ->join('el_profile AS profile', 'profile.user_id', '=', 'register.user_id')
            ->leftJoin('el_unit AS unit', 'unit.code', '=', 'profile.unit_code')
            ->where('register.course_id','=',$course_id);
            /*if (!Permission::isAdmin()){
                $query->whereIn('unit.id', $managers);
            }*/
        return $query->count();
    }

    public function user()
    {
        return $this->belongsTo(Profile::class,'user_id','user_id');
    }
}
