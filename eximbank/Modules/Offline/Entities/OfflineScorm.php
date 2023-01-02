<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineScorm extends Model
{
    protected $table = 'offline_scorms';
    protected $fillable = [
        'origin_path',
        'unzip_path',
        'index_file',
        'status',
        'error',
    ];

    public function course_activities() {
        return $this->hasMany(OfflineCourseActivityScorm::class, 'path', 'origin_path');
    }
}
