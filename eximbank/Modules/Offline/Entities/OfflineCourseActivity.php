<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Offline\Entities\OfflineActivityQuiz;
use Modules\Quiz\Entities\QuizPart;

/**
 * Modules\Offline\Entities\OfflineCourseActivity
 *
 * @property int $id
 * @property string|null $name
 * @property int $course_id
 * @property int $activity_id
 * @property int $subject_id
 * @property int|null $num_order
 * @property int|null $lesson_id
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereActivityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereLessonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereNumOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfflineCourseActivity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineCourseActivity extends Model
{
    protected $table = 'offline_course_activity';
    protected $fillable = [
        'course_id',
        'class_id',
        'schedule_id',
        'activity_id',
        'subject_id',
    ];

    public function course() {
        return $this->hasOne(OfflineCourse::class, 'id', 'course_id');
    }

    public function activity() {
        return $this->hasOne(OfflineActivity::class, 'id', 'activity_id');
    }

    public function activity_completions() {
        return $this->hasMany(OfflineCourseActivityCompletion::class, 'activity_id', 'id');
    }

    public function isComplete($user_id, $user_type = 1) {
        return $this->activity_completions()
            ->whereUserId($user_id)
            ->where('user_type', '=', $user_type)
            ->whereStatus(1)
            ->exists();
    }

    public function checkComplete($user_id = null, $user_type = null) {
        $user_id = $user_id ?? getUserId();
        $user_type = $user_type ?? getUserType();

        $activity = OfflineActivity::where('id', '=', $this->activity_id)->first();

        if (empty($activity)) {
            return false;
        }

        if (in_array($activity->code, ['url', 'file'])) {

            if (OfflineCourseActivityHistory::where('course_id', '=', $this->course_id)
                ->where('course_activity_id', '=', $this->id)
                ->where('user_id', '=', $user_id)
                ->where('user_type', '=', $user_type)
                ->exists()) {
                return true;
            }

            return false;
        }

        if ($activity->code == 'video') {
            $video = OfflineCourseActivityVideo::find($this->subject_id, ['required_video_timeout']);
            $check_finish_video = OfflineFinishVideo::where('video_id', $this->id)
                ->where('course_id', '=', $this->course_id)
                ->where('user_id', $user_id)
                ->exists();
            if ($video->required_video_timeout == 1 && $check_finish_video) {
                return true;
            } else if ($video->required_video_timeout == 1 && !$check_finish_video) {
                return false;
            } else {
                if (OfflineCourseActivityHistory::where('course_id', '=', $this->course_id)
                    ->where('course_activity_id', '=', $this->id)
                    ->where('user_id', '=', $user_id)
                    ->where('user_type', '=', $user_type)
                    ->exists()) {
                    return true;
                }
            }

            return false;
        }

        if ($activity->code == 'scorm') {

            $scorm = OfflineCourseActivityScorm::find($this->subject_id);
            if ($scorm) {
                return $scorm->checkComplete($user_id, $user_type);
            }

            return false;
        }
        if ($activity->code == 'xapi') {

            $xapi = OfflineCourseActivityXapi::find($this->subject_id);
            if ($xapi) {
                return $xapi->checkComplete($user_id, $user_type);
            }

            return false;
        }
        return false;
    }

    public static function getActivitiesByCourseLesson($lesson_id = 1, $course_id) {
        $query = OfflineCourseActivity::query();
        $query->select([
            'a.id',
            'a.name',
            'a.subject_id',
            'a.lesson_id',
            'a.class_id',
            'a.schedule_id',
            'a.num_order',
            'a.status',
            'b.id AS activity_id',
            'b.code AS activity_code',
            'b.name AS activity_name',
            'b.icon',
        ]);
        $query->from('offline_course_activity AS a')
            ->join('offline_activity AS b', 'b.id', '=', 'a.activity_id')
            ->where('a.course_id', '=', $course_id)
            ->where('a.lesson_id', '=', $lesson_id)
            ->orderBy('a.num_order', 'ASC');

        if ($query->exists()) {
            return $query->get();
        }
        return [];
    }

    public static function getByCourse($course_id, $class_id = null, $schedule_id = null, $activity_id = null, $condition = null) {
        $query = OfflineCourseActivity::query();
        $query->select([
            'a.id',
            'a.name',
            'a.subject_id',
            'a.lesson_id',
            'a.class_id',
            'a.schedule_id',
            'a.num_order',
            'a.status',
            'b.id AS activity_id',
            'b.code AS activity_code',
            'b.name AS activity_name',
            'b.icon',
        ]);
        $query->from('offline_course_activity AS a')
            ->join('offline_activity AS b', 'b.id', '=', 'a.activity_id')
            ->where('a.course_id', '=', $course_id)
            ->where('a.activity_id', '!=', 6)
            ->orderBy('a.num_order', 'ASC');

        if($class_id){
            $query->where('a.class_id', '=', $class_id);
        }

        if($schedule_id){
            $query->where('a.schedule_id', '=', $schedule_id);
        }

        if ($activity_id){
            $query->where('a.activity_id', '=', $activity_id);
        }

        if($condition){
            $query->whereIn('a.id', $condition);
        }

        $rows = $query->get();
        return $rows;
    }

    public function getLinkQuizCourse(){
        $user_type = getUserType();
        $user_id = getUserId();
        $quiz_id = OfflineActivityQuiz::find($this->subject_id, ['quiz_id'])->quiz_id;
        $part =  QuizPart::where('quiz_id', '=', $quiz_id)
            ->whereIn('id', function ($subquery) use ($user_id, $user_type, $quiz_id) {
                $subquery->select(['a.part_id'])
                    ->from('el_quiz_register AS a')
                    ->join('el_quiz_part AS b', 'b.id', '=', 'a.part_id')
                    ->where('a.quiz_id', '=', $quiz_id)
                    ->where('a.user_id', '=', $user_id)
                    ->where('a.type', '=', $user_type)
                    ->where(function ($where){
                        $where->orWhere('b.end_date', '>', date('Y-m-d H:i:s'));
                        $where->orWhereNull('b.end_date');
                    });
            })->disableCache()->first();

        if (!empty($part)) {

            if (url_mobile()){
                return route('module.quiz_mobile.doquiz.index_by_online', [$quiz_id, $part->id]);
            }

            return route('module.quiz.doquiz.index_by_online', [$quiz_id, $part->id]);
        } else {
            return '';
        }
    }
}
