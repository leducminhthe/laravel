<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineTeamsReport
 *
 * @property int $id
 * @property int $course_id
 * @property int $class_id
 * @property int $schedule_id
 * @property string $teams_id
 * @property string|null $title
 * @property int|null $total_participant
 * @property string|null $meeting_start
 * @property string|null $meeting_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereMeetingEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereMeetingStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereTeamsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereTotalParticipant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineTeamsReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineTeamsReport extends Model
{
    protected $table = "offline_teams_report";
    protected $fillable = [
        'course_id',
        'class_id',
        'schedule_id',
        'teams_id',
        'user_id',
        'full_name',
        'email',
        'join_time',
        'leave_time',
        'total_second',
        'duration',
        'role',
    ];
}
