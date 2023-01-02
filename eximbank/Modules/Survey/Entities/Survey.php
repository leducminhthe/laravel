<?php

namespace Modules\Survey\Entities;

use App\Models\BaseModel;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Survey\Entities\Survey
 *
 * @property int $id
 * @property string $name
 * @property string $start_date
 * @property string|null $end_date
 * @property int $template_id
 * @property int $status
 * @property int $more_suggestions
 * @property string $custom_template
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereCustomTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereMoreSuggestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Survey\Entities\Survey whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 */
class Survey extends BaseModel
{
    use ChangeLogs, Cachable;
    protected $table = 'el_survey';
    protected $table_name = 'Khảo sát';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'template_id',
        'status',
        'more_suggestions',
        'created_by',
        'updated_by',
        'custom_template',
        'num_popup',
        'num_notify',
    ];

    public static function getAttributeName() {
        return [
            'name' => 'Tên khảo sát',
            'start_date' => trans('latraining.start_date'),
            'end_date' => trans('latraining.end_date'),
            'template_id' => 'Mẫu khảo sát',
            'status' => trans("latraining.status"),
            'more_suggestions' => 'Đề xuất khác',
            'created_by' => trans('laother.creator'),
            'updated_by' => trans('laother.editor'),
            'custom_template' => 'Mẫu tuỳ chỉnh',
        ];
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User','el_survey_user','survey_id','user_id')
            ->withPivot('template_id','more_suggestions','send','created_at','updated_at');
    }

    public function countQuestion(){
        $count_question = SurveyQuestion::whereIn('category_id', function ($subquery){
            $subquery->select(['id']);
            $subquery->from('el_survey_template_question_category');
            $subquery->where('template_id', '=', $this->template_id);
        })->count();

        return $count_question;
    }
}
