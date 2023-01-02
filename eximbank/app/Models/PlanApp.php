<?php

namespace App\Models;

use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PlanApp
 *
 * @property int $id
 * @property int $plan_app_id
 * @property int $user_id
 * @property int $course_id
 * @property int $course_type
 * @property string|null $suggest_self
 * @property string|null $suggest_manager
 * @property int|null $evaluation_self
 * @property string|null $evaluation_manager
 * @property string|null $approved_date
 * @property string|null $evaluation_date
 * @property string|null $start_date
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereApprovedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereCourseType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereEvaluationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereEvaluationManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereEvaluationSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp wherePlanAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereSuggestManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereSuggestSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PlanApp whereUserId($value)
 * @mixin \Eloquent
 */
class PlanApp extends BaseModel
{
    use ChangeLogs, Cachable;

    protected $table = 'el_plan_app';
    protected $table_name = "Đánh giá hiệu quả đào tạo";
    protected $fillable = [
        'plan_app_id',
        'user_id',
        'course_id',
        'course_type',
        'suggest_self',
        'suggest_manager',
        'evaluation_self',
        'evaluation_manager',
        'approved_date',
        'evaluation_date',
        'start_date',
        'status',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'plan_app_id' => 'Mã Đánh giá hiệu quả đào tạo',
            'user_id' => 'Mã user id',
            'course_id' => trans('latraining.course_code'),
            'course_type' => 'Loại khóa học',
            'suggest_self' => 'Đề xuất học viên',
            'suggest_manager' => 'Đề xuất trưởng đơn vị',
            'evaluation_self' => 'Đánh giá học viên',
            'evaluation_manager' => 'Đánh giá trưởng đơn vị',
            'approved_date' => 'Ngày TĐV duyệt',
            'evaluation_date' => 'Ngày nhân viên tự đánh giá',
            'start_date' => 'Ngày bắt đầu đánh giá',
            'status' => trans("latraining.status")
        ];
    }
}
