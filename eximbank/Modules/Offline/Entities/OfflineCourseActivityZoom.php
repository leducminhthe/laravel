<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineCourseActivityZoom
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityZoom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityZoom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivityZoom query()
 * @mixin \Eloquent
 */
class OfflineCourseActivityZoom extends Model
{
    protected $table = 'offline_course_activity_zoom';
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
        'type',
        'status',
        'join_url',
        'start_url',
        'password',
        'zoom_id',
    ];
}
