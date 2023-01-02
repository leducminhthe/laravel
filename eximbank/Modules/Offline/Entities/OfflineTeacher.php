<?php

namespace Modules\Offline\Entities;

use App\Models\CacheModel;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineTeacher
 *
 * @property int $id
 * @property int $course_id
 * @property int $teacher_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineTeacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineTeacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineTeacher query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineTeacher whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineTeacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineTeacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineTeacher whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineTeacher whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineTeacher extends Model
{
    use ChangeLogs;
    protected $table = 'el_offline_course_teachers';
    protected $table_name = 'Giảng viên Khóa học tập trung';
    protected $fillable = [
        'class_id',
        'course_id',
        'teacher_id',
        'note',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => trans('lamenu.course'),
            'teacher_id' => trans('latraining.teacher'),
        ];
    }

    public static function checkExists($course_id,$class_id, $teacher_id){
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        $query->where('teacher_id', '=', $teacher_id);
        $query->where('class_id', '=', $class_id);
        return $query->exists();
    }

    public static function getTeachers($course_id)
    {
        $teacher_name = OfflineTeacher::query()
            ->from('el_offline_course_teachers AS a')
            ->join('el_training_teacher AS b', 'b.id', '=', 'a.teacher_id')
            ->where('a.course_id', '=', $course_id)
            ->pluck('b.name')
            ->toArray();

        return implode(', ', $teacher_name);
    }
}
