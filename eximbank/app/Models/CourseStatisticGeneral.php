<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseStatisticGeneral
 *
 * @property int|null $course_held khóa học đã tổ chức
 * @property int|null $course_not_held khóa học chưa tổ chức
 * @property int|null $course_pending khóa học chờ duyệt
 * @property int|null $course_deny khóa học bị từ chối
 * @property int|null $course_total Tổng khóa học
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatisticGeneral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatisticGeneral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatisticGeneral query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatisticGeneral whereCourseDeny($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatisticGeneral whereCourseHeld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatisticGeneral whereCourseNotHeld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatisticGeneral whereCoursePending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseStatisticGeneral whereCourseTotal($value)
 * @mixin \Eloquent
 */
class CourseStatisticGeneral extends Model
{
    use Cachable;
    protected $table='el_course_statistic_general';
    protected $fillable = [
        'course_held',
        'course_not_held',
        'course_pending',
        'course_deny',
        'course_total',
    ];
}
