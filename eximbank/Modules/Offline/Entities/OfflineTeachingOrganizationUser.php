<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationUser extends Model
{
    protected $table = 'el_offline_teaching_organization_user';
    protected $fillable = [
        'user_id',
        'course_id',
        'send',
        'template_id',
    ];
    protected $primaryKey = 'id';
}
