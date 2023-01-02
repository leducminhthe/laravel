<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineCourseDocument extends Model
{
    protected $table = 'offline_course_document';
    protected $fillable = [
        'course_id',
        'name',
        'document',
    ];
}
