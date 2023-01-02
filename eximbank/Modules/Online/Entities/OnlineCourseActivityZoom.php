<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseActivityZoom
 *
 * @property int $id
 * @property int $course_id
 * @property string $topic
 * @property string $description
 * @property string $start_time
 * @property int|null $duration
 * @property int|null $type
 * @property string|null $status
 * @property string|null $join_url
 * @property string|null $start_url
 * @property string|null $password
 * @property int|null $zoom_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Online\Entities\OnlineCourseActivity|null $course_activity
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom query()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereJoinUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereStartUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityZoom whereZoomId($value)
 * @mixin \Eloquent
 */
class OnlineCourseActivityZoom extends Model
{
    protected $table = 'online_course_activity_zoom';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'topic',
        'description',
        'start_time',
        'duration',
        'type',
        'status',
        'join_url',
        'start_url',
        'password',
        'zoom_id',
    ];

    public function course_activity() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourseActivity', 'subject_id', 'id');
    }
}
