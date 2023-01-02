<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationUserQuestion extends Model
{
    protected $table = 'el_offline_teaching_organization_user_question';
    protected $fillable = [
        'teaching_organization_category_id',
        'question_id',
        'question_code',
        'question_name',
        'answer_essay',
        'type',
        'multiple',
        'teacher_id',
    ];
    protected $primaryKey = 'id';
}
