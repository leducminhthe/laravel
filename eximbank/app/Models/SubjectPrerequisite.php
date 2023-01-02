<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectPrerequisite extends Model
{
    protected $table = 'subject_prerequisites';
    protected $table_name = "Điều kiện tiên quyết";
    protected $primaryKey = 'id';
    protected $fillable = [
        'subject_id',
        'subject_prerequisite',
        'finish_and_score',
        'date_finish_prerequisite',
        'score_prerequisite',
        'select_subject_prerequisite',
        'status_title',
        'title_id',
        'select_title',
        'date_title_appointment',
        'select_date_title_appointment',
        'status_join_company',
        'join_company',
    ];
}
