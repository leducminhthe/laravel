<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineCourseDocument extends Model
{
    protected $table = 'online_course_document';
    protected $fillable = [
        'course_id',
        'name',
        'document',
    ];
}
