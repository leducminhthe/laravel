<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseActivityUrl extends Model
{
    protected $table = 'offline_course_activity_url';
    protected $primaryKey = 'id';
    protected $fillable = [
        'url',
        'description',
        'page',
    ];
}
