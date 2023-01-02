<?php

namespace Modules\CoursePlan\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CoursePlanTeacher extends Model
{
    use Cachable;
    protected $table = 'el_course_plan_teacher';
    protected $table_name = 'Giảng viên Kế hoạch đào tạo tháng';
    protected $fillable = [
        'course_id',
        'course_type',
        'teacher_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => trans('lamenu.course'),
            'teacher_id' => trans('lareport.teacher'),
        ];
    }

    public static function checkExists($course_type, $course_id, $teacher_id){
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        $query->where('course_type', '=', $course_type);
        $query->where('teacher_id', '=', $teacher_id);
        return $query->exists();
    }
}
