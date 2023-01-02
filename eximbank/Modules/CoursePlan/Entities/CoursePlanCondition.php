<?php

namespace Modules\CoursePlan\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CoursePlanCondition extends Model
{
    use Cachable;
    protected $table = 'el_course_plan_condition';
    protected $table_name = 'Điều kiện hoàn thành Kế hoạch đào tạo tháng';
    protected $fillable = [
        'course_id',
        'course_type',
        'ratio',
        'minscore',
        'survey',
        'certificate',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => trans('lamenu.course'),
            'ratio' => 'Tỉ lệ %',
            'minscore' => 'Điểm tối thiểu',
            'survey' => 'Thực hiện đánh giá',
            'certificate' => 'Chứng chỉ khóa học',

        ];
    }

    public static function checkExists($course_type, $course_id)
    {
        $query = self::query();
        $query->where('course_id', '=', $course_id);
        $query->where('course_type', '=', $course_type);
        return $query->exists();
    }
}
