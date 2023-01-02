<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationUserCategory extends Model
{
    protected $table = 'el_offline_teaching_organization_user_category';
    protected $fillable = [
        'teaching_organization_user_id',
        'category_id',
        'category_name',
        'rating_teacher',
    ];
    protected $primaryKey = 'id';
}
