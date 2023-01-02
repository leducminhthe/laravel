<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineTeamsAttendanceReport
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport query()
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereUpdatedAt($value)
 * @property int $course_id
 * @property int $class_id
 * @property int $schedule_id
 * @property string $teams_id
 * @property string|null $user_id
 * @property string|null $full_name
 * @property string|null $email
 * @property string|null $join_time
 * @property string|null $leave_time
 * @property int|null $total_second
 * @property int|null $duration thời gian giây
 * @property string|null $role vai trò
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereJoinTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereLeaveTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereTeamsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereTotalSecond($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsAttendanceReport whereUserId($value)
 */
class OfflineTeamsAttendanceReport extends Model
{
    protected $table = 'offline_teams_attendance_report';
    protected $fillable = [
        'course_id',
        'class_id',
        'schedule_id',
        'teams_id',
        'user_id',
        'user_teams_id',
        'full_name',
        'email',
        'join_time',
        'leave_time',
        'total_second',
        'duration',
        'role',
    ];
}
