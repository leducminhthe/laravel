<?php

namespace Modules\Offline\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;


/**
 * Modules\Offline\Entities\OfflineCourseLesson
 *
 * @property int $id
 * @property int $course_id
 * @property string $lesson_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseLesson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseLesson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseLesson query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseLesson whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseLesson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseLesson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseLesson whereLessonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseLesson whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineCourseLesson extends Model
{
    protected $table = 'offline_course_lesson';
    protected $table_name = 'Bài học Khóa học online';
    protected $fillable = [
        'course_id',
        'class_id',
        'schedule_id',
        'lesson_name',
    ];
    public static function getAttributeName() {
        return [
            'lesson_name' => 'Tên bài học',
        ];
    }
}
