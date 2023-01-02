<?php

namespace Modules\Offline\Entities;
use App\Models\BaseModel;
use App\Models\CacheModel;
use App\Models\Categories\TrainingTeacher;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modules\Offline\Entities\OfflineSchedule
 *
 * @property int $id
 * @property int|null $schedule_parent_id
 * @property int $class_id
 * @property int $course_id
 * @property string $start_time
 * @property string $end_time
 * @property string $lesson_date
 * @property int|null $teacher_main_id Giảng viên chính
 * @property string|null $teach_id Trợ giảng
 * @property string|null $cost_teacher_main Chi phí giảng viên chính
 * @property float|null $cost_teach_type Chi phí trợ giảng
 * @property int $total_lessons
 * @property int|null $training_location_id Địa điểm đào tạo
 * @property int $cost_by Chi phí theo. 1 => Giờ. 2 => Số Sao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $type_study
 * @property float|null $condition_complete_teams
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereConditionCompleteTeams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereCostBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereCostTeachType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereCostTeacherMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereLessonDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereScheduleParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereTeachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereTeacherMainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereTotalLessons($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereTrainingLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereTypeStudy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineSchedule extends BaseModel
{
//    use Cachable;
    protected $table = 'el_offline_schedule';
    protected $table_name = 'Lịch giảng viên Khóa học tập trung';
    protected $fillable = [
        'class_id',
        'course_id',
        'start_time',
        'end_time',
        'lesson_date',
        'end_date',
        'teacher_main_id',
        'teach_id',
        'cost_teacher_main',
        'cost_teach_type',
        'total_lessons',
        'session',
        'cost_by',
        'type_study',
        'condition_complete_teams',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'course_id' => trans('lamenu.course'),
            'start_time' => 'Thời gian bắt đầu',
            'end_time' => 'Thời gian kết thúc',
            'lesson_date' => 'Ngày học',
            'teacher_id' => 'Giảng viên chính',
            'teach_id' => 'Trợ giảng',
            'cost_teacher' => trans('latraining.lecturer_fees'),
            'total_lessons' => 'Số tiết',
            'cost_by' => 'Chi phí theo',
            'type_study' => 'Loại đào tạo',
            'start_time_3' => 'Thời gian bắt đầu',
            'end_time_3' => 'Thời gian kết thúc',
            'lesson_date_3' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
        ];
    }

    public static function getTeacher($course_id, $teacherIsset = null){
        $query = DB::query();
        $query->select([
            'a.id',
            'a.name',
            'b.tnt',
        ]);
        $query->from('el_training_teacher AS a');
        $query->leftJoin('el_offline_course_teachers AS b', 'b.teacher_id', '=', 'a.id');
        $query->where('b.course_id', '=', $course_id);
        if(is_array($teacherIsset)) {
            $query->whereNotIn('a.id', $teacherIsset);
        }else{
            $query->where('a.id', '!=', $teacherIsset);
        }
        return $query->get();
    }

    public static function getSchedules($course_id)
    {
        $query = self::query();
        $query->select([
            '*',
            /*\DB::raw('CAST(lesson_date as datetime) + CAST(start_time as datetime) as schedule_time')*/
            ]);
        $query->where('course_id','=',$course_id);
        $query->orderBy('lesson_date');
        $query->orderBy('start_time');
        return $query->get();
    }
    public static function  getSchedulesOffline($course_id,$class_id)
    {
        $query = self::query();
        $query->select([
            '*',
        ]);
        $query->where('course_id','=',$course_id);
        $query->where('class_id','=',$class_id);
        $query->where('type_study','=',1);
        $query->orderBy('lesson_date');
        $query->orderBy('start_time');
        return $query->get();
    }
    public static function getMinSchedules($course_id)
    {
        $min_lesson_date = self::where('course_id','=',$course_id)->selectRaw('MIN(CAST(lesson_date as datetime) + CAST(start_time as datetime)) as schedule_id')->value('schedule_id');
        $schedule_id = self::where('course_id', '=', $course_id)
            ->whereRaw("(CAST(lesson_date as datetime) + CAST(start_time as datetime))='".$min_lesson_date."'")->value('id');
        return $schedule_id;
    }
}
