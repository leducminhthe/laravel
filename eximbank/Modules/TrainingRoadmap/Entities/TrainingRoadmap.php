<?php

namespace Modules\TrainingRoadmap\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\TrainingRoadmap\Entities\TrainingRoadmap
 *
 * @property int $id
 * @property int|null $training_program_id
 * @property int $title_id
 * @property int $subject_id
 * @property int|null $completion_time
 * @property int|null $order
 * @property string|null $content
 * @property int $training_form
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereCompletionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereTrainingForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereTrainingProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingRoadmap whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Categories\Subject|null $subject
 */
class TrainingRoadmap extends BaseModel
{
    use Cachable;
    protected $table = 'el_trainingroadmap';
    protected $table_name = 'Tháp đào tạo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title_id',
        'subject_id',
        'level_subject_id',
        'course_id',
        'completion_time',
        'order',
        'content',
        'training_program_id',
        'training_form',
    ];

    public function subject() {
        return $this->hasOne('App\Models\Categories\Subject', 'id', 'subject_id');
    }

    public static function getAttributeName() {
        return [
            'title_id'=> trans('lacategory.title_code'),
            'subject_id'=>'Mã học phần',
            'course_id' => trans('lamenu.course'),
            'training_program_id' => trans('latraining.training_program'),
            'training_form' => trans('latraining.training_type'),
        ];
    }

    public static function checkSubjectExits($subject_id, $title_id, $exclude_id = null){
        $query = self::query();
        $query->where('subject_id', '=', $subject_id);
        $query->where('title_id','=',$title_id);
        if ($exclude_id) {
            $query->where('id','!=',$exclude_id);
        }
        return $query->exists();
    }
    public static function getSubjectByTitle($request)
    {
        $title_id = $request->title_id;
        $data = TrainingRoadmap::query()
            ->from('el_trainingroadmap as a')
            ->join('el_subject as b','a.subject_id','b.id')->select('b.code','b.name')
            ->where('a.title_id','=',$title_id)->orderBy('order')->get();
        return json_result($data);
    }
}
