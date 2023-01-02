<?php

namespace Modules\Offline\Entities;

use App\Models\CacheModel;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineAttendance
 *
 * @property int $id
 * @property int|null $class_id
 * @property int $register_id
 * @property int $schedule_id
 * @property int $course_id
 * @property int|null $user_id
 * @property int|null $percent Phần trăm tham gia
 * @property string|null $note
 * @property int $status
 * @property int|null $absent_id
 * @property int|null $absent_reason_id
 * @property int|null $discipline_id
 * @property string|null $type Loại điểm danh: 1.HVQRC, 2.GVQRC, 3.Manual, 4.Edit manual
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance all($columns = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance avg($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance cache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance cachedValue(array $arguments, string $cacheKey)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance count($columns = '*')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance disableCache()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance disableModelCaching()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance exists()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance flushCache(array $tags = [])
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance getModelCacheCooldown(\Illuminate\Database\Eloquent\Model $instance)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance inRandomOrder($seed = '')
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance insert(array $values)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance isCachable()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance max($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance min($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance newModelQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance newQuery()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance query()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance sum($column)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance truncate()
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereAbsentId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereAbsentReasonId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereClassId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereCourseId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereCreatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereDisciplineId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereNote($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance wherePercent($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereRegisterId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereScheduleId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereStatus($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereType($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereUpdatedAt($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance whereUserId($value)
 * @method static \GeneaLabs\LaravelModelCaching\CachedBuilder|OfflineAttendance withCacheCooldownSeconds(?int $seconds = null)
 * @mixin \Eloquent
 */
class OfflineAttendance extends Model
{
    use ChangeLogs, Cachable;
    protected $table = 'el_offline_attendance';
    protected $table_name = 'Điểm danh khóa học tập trung';
    protected $fillable = [
        'register_id',
        'schedule_id',
        'course_id',
        'class_id',
        'user_id',
        'absent_reason_id',
        'absent_id',
        'discipline_id',
        'percent',
        'status',
        'note',
        'reference',
        'type',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'register_id' => 'Học viên ghi danh',
            'schedule_id' => 'Lịch học',
            'percent' => 'Phần trăm tham gia',
            'note' => trans('latraining.note'),
            'reference' => 'Đơn xin phép',
        ];
    }

    public static function checkExists($register_id, $schedule_id){
        $query = self::query();
        $query->where('schedule_id', '=', $schedule_id);
        $query->where('register_id', '=', $register_id);
        return $query->first();
    }

    public static function countAttendance($course_id)
    {
        $query = OfflineRegister::query()
            ->from('el_offline_register as a')
            ->join('el_offline_attendance as b','a.id','b.register_id')
            ->where('a.course_id','=', $course_id)
            ->distinct()
            ->count('a.user_id');
        return $query;
    }

    public static function updateAttendance($user_id,$course_id,$schedule_id,$class_id, $type = null)
    {
        $register = OfflineRegister::where(['user_id'=>$user_id,'course_id'=>$course_id,'class_id'=>$class_id])->where('status','=',1)->first(['id', 'class_id']);
        if ($register)
            return OfflineAttendance::updateOrCreate(
                [
                    'course_id' => $course_id,
                    'class_id' => $register->class_id,
                    'register_id' => $register->id,
                    'schedule_id' => $schedule_id,
                ],
                [
                    'course_id' => $course_id,
                    'class_id' => $register->class_id,
                    'register_id' => $register->id,
                    'schedule_id' => $schedule_id,
                    'user_id' => $user_id,
                    'status' => 1,
                    'percent' => 100,
                    'type' => $type,
                ]
            );
        else  return false;
    }
}
