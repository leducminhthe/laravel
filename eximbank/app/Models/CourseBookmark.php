<?php

namespace App\Models;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CourseBookmark
 *
 * @property int $id
 * @property int $course_id
 * @property int $type
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CourseBookmark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CourseBookmark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CourseBookmark query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CourseBookmark whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CourseBookmark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CourseBookmark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CourseBookmark whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CourseBookmark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CourseBookmark whereUserId($value)
 * @mixin \Eloquent
 */
class CourseBookmark extends Model
{
    use Cachable;
    protected $table = 'el_course_bookmark';
    protected $table_name = "Đánh dấu khóa học";
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'type',
        'user_id'
    ];

    public static function checkExist($course_id, $course_type){
        $check = self::query()
            ->where('course_id', '=', $course_id)
            ->where('type', '=', $course_type)
            ->where('user_id', '=', profile()->user_id);

        return $check->exists();
    }
}
