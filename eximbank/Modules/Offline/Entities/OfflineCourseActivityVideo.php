<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivityVideo extends Model
{
    protected $table = 'offline_course_activity_video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'extension',
        'path',
        'description',
    ];

    public function warehouse() {
        return $this->hasOne('App\Model\Warehouse', 'file_path', 'path');
    }

    public function getLinkPlay() {
        $storage = \Storage::disk('local');
        $file = encrypt_array([
            'path' => $storage->path('uploads/'.$this->path),
        ]);

        return route('module.online.view_video', [$file]);
    }
}
