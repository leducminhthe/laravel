<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationUserAnswer extends Model
{
    protected $table = 'el_offline_teaching_organization_user_answer';
    protected $fillable = [
        'teaching_organization_question_id',
        'answer_id',
        'answer_code',
        'answer_name',
        'text_answer',
        'check_answer_matrix',
        'answer_matrix',
        'is_text',
        'is_check',
        'is_row',
        'icon',
    ];
    protected $primaryKey = 'id';
}
