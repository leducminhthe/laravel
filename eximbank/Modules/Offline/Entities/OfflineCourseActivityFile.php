<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivityFile extends Model
{
    protected $table = 'offline_course_activity_file';
    protected $primaryKey = 'id';
    protected $fillable = [
        'extension',
        'path',
        'description',
    ];

    public function warehouse() {
        return $this->hasOne('App\Models\Warehouse', 'file_path', 'path');
    }

    public function course_activity() {
        return $this->hasOne(OfflineCourseActivity::class, 'subject_id', 'id');
    }

    /**
     * Check complete.
     * @param int $user_id
     * @return bool
     * */
    public function checkComplete($user_id) {
        if (OfflineCourseActivityHistory::where('course_id', '=', $this->course_id)
            ->where('course_activity_id', '=', $this->course_activity->id)
            ->where('user_id', '=', $user_id)
            ->exists()) {
            return true;
        }

        return false;
    }
}
