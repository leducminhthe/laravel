<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationCategory extends Model
{
    protected $table = 'el_offline_teaching_organization_category';
    protected $fillable = [
        'course_id',
        'name',
        'template_id',
        'rating_teacher',
    ];
    protected $primaryKey = 'id';
}
