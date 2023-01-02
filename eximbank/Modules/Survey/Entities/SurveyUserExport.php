<?php

namespace Modules\Survey\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\SurveyUserExport
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyUserExport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyUserExport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyUserExport query()
 * @mixin \Eloquent
 */
class SurveyUserExport extends Model
{
    use Cachable;
    protected $table = 'el_survey_user_export';
    protected $fillable = [
        'user_id',
        'survey_id',
        'title',
        'content',
    ];
    protected $primaryKey = 'id';
}
