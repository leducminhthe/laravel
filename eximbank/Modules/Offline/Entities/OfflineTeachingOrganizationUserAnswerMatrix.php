<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationUserAnswerMatrix extends Model
{
    protected $table = 'el_offline_teaching_organization_user_answer_matrix';
    protected $fillable = [
        'teaching_organization_question_id',
        'answer_code',
        'answer_row_id',
        'answer_col_id',
    ];
    protected $primaryKey = 'id';
}
