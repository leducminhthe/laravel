<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineTeachingOrganizationAnswerMatrix extends Model
{
    protected $table = 'el_offline_teaching_organization_answer_matrix';
    protected $fillable = [
        'course_id',
        'code',
        'question_id',
        'answer_row_id',
        'answer_col_id',
    ];
    protected $primaryKey = 'id';
}
