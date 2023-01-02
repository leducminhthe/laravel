<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseActivityXapi
 *
 * @property int $id
 * @property int $course_id
 * @property int $xapi_id
 * @property string $path
 * @property int $max_attempt 0: Không giới hạn
 * @property int $what_grade 1: Lần cao nhất, 2: trung bình, 3: lần đầu, 4: lần cuối
 * @property int $max_score điểm tối đa
 * @property int $score_required nhận điểm để hoàn thành
 * @property int $status_passed trạng thái đạt
 * @property int $status_completed trạng thái hoàn thành
 * @property int $new_attempt_required 1: khi có kết quả, 2: luôn luôn
 * @property int $min_score_required điểm tối thiểu để hoàn thành
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\ActivityScormAttempt[] $attempts
 * @property-read int|null $attempts_count
 * @property-read \Modules\Online\Entities\OnlineCourse|null $course
 * @property-read \Modules\Online\Entities\OnlineCourseActivity|null $course_activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\ActivityScormScore[] $scores
 * @property-read int|null $scores_count
 * @property-read \Modules\Online\Entities\Scorm|null $scorm
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\ActivityScormUser[] $users
 * @property-read int|null $users_count
 * @property-read \App\Warehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi query()
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereMaxAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereMaxScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereMinScoreRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereNewAttemptRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereScoreRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereStatusCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereStatusPassed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereWhatGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OnlineCourseActivityXapi whereXapiId($value)
 * @mixin \Eloquent
 */
class OnlineCourseActivityXapi extends Model
{
    protected $table = 'el_online_course_activity_xapi';
    protected $fillable = [
        'description',
        'xapi_id',
        'path',
        'max_attempt',
        'what_grade',
        'max_score',
        'score_required',
        'status_passed',
        'status_completed',
        'new_attempt_required',
        'min_score_required',
        'new_attempt_required',
    ];

    public function course_activity() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourseActivity', 'subject_id', 'id');
    }

    public function course() {
        return $this->hasOne('Modules\Online\Entities\OnlineCourse', 'id', 'course_id');
    }

    public function xapi() {
        return $this->hasOne(Xapi::class, 'origin_path', 'path');
    }

    public function warehouse() {
        return $this->hasOne('App\Models\Warehouse', 'file_path', 'path');
    }

    public function attempts() {
        return $this->hasMany(ActivityXapiAttempt::class, 'activity_id', 'id');
    }

    public function scores() {
        return $this->hasMany(ActivityXapiScore::class, 'activity_id', 'id');
    }

    public function users() {
        return $this->hasMany('Modules\Online\Entities\ActivityScormUser', 'activity_id', 'id');
    }

    /**
     * @param int $user_id
     * @return bool
     * */
    public function checkComplete($user_id, $user_type = 1) {
        $result = [];

        if ($this->score_required == 0) {
            $viewed = ActivityXapiAttempt::whereActivityId($this->id)
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->get();

            if (!empty($viewed)) {
                $result[] = true;
            }
            else {
                $result[] = false;
            }
        }
        else {
            $score = null;
            if ($this->scores()
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->count('id') > 0) {

                switch ($this->what_grade) {
                    case 1: $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->max('score');
                    break;
                    case 2: $score = $this->scores()
                            ->whereUserId($user_id)
                            ->where('user_type' ,'=', $user_type)
                            ->sum('score') / $this->scores()
                            ->whereUserId($user_id)
                            ->where('user_type' ,'=', $user_type)
                            ->count('id');
                        break;
                    case 3: $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->first(['score'])->score;
                    break;
                    case 4: $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->orderBy('id', 'DESC')
                        ->first(['score'])->score;
                    break;
                }
            }

            if (is_null($score)) {
                $result[] = false;
            }
            else {
                if ($score >= $this->min_score_required) {
                    $result[] = true;
                }
                else {
                    $result[] = false;
                }
            }
        }

        if ($this->status_passed || $this->status_completed) {
            $result[] = $this->scores()
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->where('status', 'like', '%passed%')
                ->exists();
        }

        /*
         *  Nếu có bất cứ điều kiện nào false thì return false
         * */
        if (in_array(false, $result)) {
            return false;
        }

        return true;
    }

    public function getScoreXapi($user_id, $user_type = 1){
        $score = null;
        if ($this->scores()
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->count('id') > 0) {

            switch ($this->what_grade) {
                case 1: //điểm cao nhất
                    $score = $this->scores()
                    ->whereUserId($user_id)
                    ->where('user_type' ,'=', $user_type)
                    ->max('score');
                    break;
                case 2: //điểm trung bình
                    $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->sum('score') / $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->count('id');
                    break;
                case 3: //điểm đầu tiên
                    $score = $this->scores()
                    ->whereUserId($user_id)
                    ->where('user_type' ,'=', $user_type)
                    ->first(['score'])->score;
                    break;
                case 4: // điểm cuối
                    $score = $this->scores()
                    ->whereUserId($user_id)
                    ->where('user_type' ,'=', $user_type)
                    ->orderBy('id', 'DESC')
                    ->first(['score'])->score;
                    break;
            }
        }

        return $score;
    }
}
