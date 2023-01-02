<?php

namespace Modules\CourseEducatePlan\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CourseEducatePlanTeacher extends Model
{
    use Cachable;
    protected $table = 'el_course_educate_plan_teacher';
    protected $fillable = [
        'course_id',
        'teacher_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' =>trans('lacourse.course'),
            'teacher_id' => trans('lareport.teacher'),
        ];
    }

    public static function checkExists($course_id, $teacher_id){
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        $query->where('teacher_id', '=', $teacher_id);
        return $query->exists();
    }
}
