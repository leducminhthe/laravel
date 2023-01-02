<?php

namespace Modules\Survey\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SurveyPopup extends BaseModel
{
    use Cachable;
    protected $table = 'el_survey_popup';
    protected $fillable = [
        'survey_id',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
        'unit_by',
    ];
    protected $primaryKey = 'id';
}
