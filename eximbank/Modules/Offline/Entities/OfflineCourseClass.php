<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;


/**
 * Modules\Offline\Entities\OfflineCourseClass
 *
 * @property int $id
 * @property int $course_id
 * @property string $code
 * @property string $name
 * @property int|null $students số lượng học viên
 * @property string|null $start_date Thời gian bắt đầu tổ chức
 * @property string|null $end_date Thời gian kết thúc
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass whereStudents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseClass whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineCourseClass extends Model
{
    protected $table="offline_course_class";
    protected $fillable = [
        'course_id',
        'code',
        'name',
        'students',
        'start_date',
        'end_date',
        'default',
    ];

    public static function getAttributeName() {
        return [
            'name' => trans('latraining.classroom'),
            'students' => trans('latraining.student'),
            'start_date' => trans('latraining.start_date'),
            'end_date' => trans('latraining.end_date'),
        ];
    }
}
