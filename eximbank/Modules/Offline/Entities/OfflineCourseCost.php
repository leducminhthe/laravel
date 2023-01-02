<?php

namespace Modules\Offline\Entities;

use App\Models\CacheModel;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Modules\Indemnify\Entities\Indemnify;

/**
 * Modules\Offline\Entities\OfflineCourseCost
 *
 * @property int $id
 * @property int $course_id
 * @property int $cost_id
 * @property int $plan_amount
 * @property int $actual_amount
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost whereActualAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost whereCostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost wherePlanAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineCourseCost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineCourseCost extends Model
{
    use ChangeLogs, Cachable;
    protected $table = "el_offline_course_cost";
    protected $table_name = 'Chi phí khóa học tập trung';
    protected $fillable = [
        'course_id',
        'cost_id',
        'plan_amount',
        'actual_amount',
        'notes',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'cost_id' => 'Loại chi phí',
            'course_id' => trans('lamenu.course'),
            'plan_amount'  => 'Tiền tạm tính',
            'actual_amount' => 'Tiền thực thi',
            'notes' =>trans('latraining.note'),
        ];
    }

    public static function checkExists($course_id, $cost_id){
        $query = self::query();
        $query->where('cost_id', '=', $cost_id);
        $query->where('course_id', '=', $course_id);
        return $query->exists();
    }

    public static function sumActualAmount($course_id, $type = null)
    {
        $query = self::query();
        if ($type){
            $query->leftJoin('el_training_cost as a', 'a.id', '=', 'el_offline_course_cost.cost_id');
            $query->where('a.type', '=', $type);
        }
        $query->where('course_id','=', $course_id);
        return $query->sum('actual_amount');
    }

    public static function costHeld($course_id)
    {
        $indemnify = Indemnify::where(['course_id'=>$course_id])->select('course_cost')->first();

        return $indemnify ? $indemnify->course_cost : 0;
    }
}
