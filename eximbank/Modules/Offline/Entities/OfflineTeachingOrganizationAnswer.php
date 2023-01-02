<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationAnswer extends Model
{
    protected $table = 'el_offline_teaching_organization_answer';
    protected $fillable = [
        'course_id',
        'code',
        'name',
        'question_id',
        'is_text',
        'is_row',
        'icon',
    ];
    protected $primaryKey = 'id';
}
