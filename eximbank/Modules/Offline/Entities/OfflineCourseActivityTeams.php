<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineCourseActivityTeams
 *
 * @property int $id
 * @property int $course_id
 * @property string $topic
 * @property string|null $description
 * @property string $start_time
 * @property string $end_time
 * @property int|null $duration
 * @property int|null $meeting_code
 * @property string|null $status
 * @property string|null $join_url
 * @property string|null $join_web_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereJoinUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereJoinWebUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereMeetingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $class_id Lớp học
 * @property int|null $schedule_id Buổi học
 * @property string|null $teams_id
 * @property int|null $report
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityTeams whereTeamsId($value)
 */
class OfflineCourseActivityTeams extends Model
{
    protected $table = 'offline_course_activity_teams';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'class_id',
        'schedule_id',
        'topic',
        'description',
        'start_time',
        'end_time',
        'duration',
        'meeting_code',
        'join_url',
        'join_web_url',
        'teams_id',
        'event_id'
    ];
}
