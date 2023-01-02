<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineXapi extends Model
{
    protected $table = 'offline_xapi';
    protected $fillable = [
        'origin_path',
        'unzip_path',
        'index_file',
        'status',
        'error',
    ];

    public function course_activities() {
        return $this->hasMany(OfflineCourseActivityXapi::class, 'path', 'origin_path');
    }
}
