<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationQuestion extends Model
{
    protected $table = 'el_offline_teaching_organization_question';
    protected $fillable = [
        'course_id',
        'code',
        'name',
        'category_id',
        'type',
        'multiple',
        'obligatory',
    ];
    protected $primaryKey = 'id';
}
