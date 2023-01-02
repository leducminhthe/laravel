<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\ActivityXapiAttempt
 *
 * @property int $id
 * @property int $activity_id
 * @property int $user_id
 * @property int $course_id
 * @property int $user_type
 * @property int $attempt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Online\Entities\OnlineCourseActivityXapi|null $activity_xapi
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt whereAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt whereUserType($value)
 * @mixin \Eloquent
 * @property string|null $uuid
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityXapiAttempt whereUuid($value)
 */
class ActivityXapiAttempt extends Model
{
    protected $table = 'el_activity_xapi_attempts';
    protected $fillable = [
        'activity_id',
        'user_id',
        'user_type',
        'attempt',
        'course_id',
        'uuid',
    ];

    public function activity_xapi() {
        return $this->hasOne(OnlineCourseActivityXapi::class, 'id', 'activity_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
