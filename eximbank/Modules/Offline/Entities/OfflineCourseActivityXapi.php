<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivityXapi extends Model
{
    protected $table = 'offline_course_activity_xapi';
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
        return $this->hasOne(OfflineCourseActivity::class, 'subject_id', 'id');
    }

    public function course() {
        return $this->hasOne(OfflineCourse::class, 'id', 'course_id');
    }

    public function xapi() {
        return $this->hasOne(OfflineXapi::class, 'origin_path', 'path');
    }

    public function warehouse() {
        return $this->hasOne('App\Models\Warehouse', 'file_path', 'path');
    }

    public function attempts() {
        return $this->hasMany(OfflineActivityXapiAttempt::class, 'activity_id', 'id');
    }

    public function scores() {
        return $this->hasMany(OfflineActivityXapiScore::class, 'activity_id', 'id');
    }

    /**
     * @param int $user_id
     * @return bool
     * */
    public function checkComplete($user_id, $user_type = 1) {
        $result = [];

        if ($this->score_required == 0) {
            $viewed = OfflineActivityXapiAttempt::whereActivityId($this->id)
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
