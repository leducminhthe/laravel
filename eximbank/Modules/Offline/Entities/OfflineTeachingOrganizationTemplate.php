<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationTemplate extends Model
{
    protected $table = 'el_offline_teaching_organization_template';
    protected $fillable = [
        'course_id',
        'code',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];
    protected $primaryKey = 'id';
}
