<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivityScorm extends Model
{
    protected $table = 'offline_course_activity_scorms';
    protected $fillable = [
        'description',
        'scorm_id',
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
        'type_result',
    ];
    protected $casts = [ 'type_result' => 'integer', ];

    public function course_activity() {
        return $this->hasOne(OfflineCourseActivity::class, 'subject_id', 'id');
    }

    public function course() {
        return $this->hasOne(OfflineCourse::class, 'id', 'course_id');
    }

    public function scorm() {
        return $this->hasOne(OfflineScorm::class, 'origin_path', 'path');
    }

    public function warehouse() {
        return $this->hasOne('App\Models\Warehouse', 'file_path', 'path');
    }

    public function attempts() {
        return $this->hasMany(OfflineActivityScormAttempt::class, 'activity_id', 'id');
    }

    public function scores() {
        return $this->hasMany(OfflineActivityScormScore::class, 'activity_id', 'id');
    }

    /**
     * @param int $user_id
     * @return bool
     * */
    public function checkComplete($user_id, $user_type = 1) {
        $result = [];

        if ($this->score_required == 0) {
            $course_activity = OfflineCourseActivity::whereSubjectId($this->id)->where('course_id', $this->course_id)->where('activity_id', 1)->first();

            $viewed = OfflineCourseActivityHistory::whereCourseId($this->course_id)
                ->whereCourseActivityId(@$course_activity->id)
                ->whereUserId($user_id)
                ->exists();
            $viewed_scorm_attempt = OfflineActivityScormAttempt::whereActivityId($this->id)
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->exists();

            if ($viewed && $viewed_scorm_attempt) {
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
                    //Lần cao nhất
                    case 1: $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->max('score');
                    break;
                    //trung bình
                    case 2: $score = $this->scores()
                            ->whereUserId($user_id)
                            ->where('user_type' ,'=', $user_type)
                            ->sum('score') / $this->scores()
                            ->whereUserId($user_id)
                            ->where('user_type' ,'=', $user_type)
                            ->count('id');
                    break;
                    //lần đầu
                    case 3: $score = $this->scores()
                        ->whereUserId($user_id)
                        ->where('user_type' ,'=', $user_type)
                        ->first(['score'])->score;
                    break;
                    //lần cuối
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

        if ($this->status_passed == 1 || $this->status_completed == 1) {
            $result[] = $this->scores()
                ->whereUserId($user_id)
                ->where('user_type' ,'=', $user_type)
                ->where(function ($sub) {
                    $sub->where('status', 'like', '%completed%');
                    $sub->orwhere('status', 'like', '%passed%');
                })
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

    public function getScoreScorm($user_id, $user_type = 1){
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

        return $score;
    }
}
