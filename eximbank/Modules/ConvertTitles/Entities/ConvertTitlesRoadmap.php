<?php

namespace Modules\ConvertTitles\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ConvertTitlesRoadmap extends Model
{
    use Cachable;
    protected $table = 'el_convert_titles_roadmap';
    protected $fillable = [
        'title_id',
        'subject_id',
        'completion_time',
        'order',
        'content',
        'training_program_id',
        'training_form',
    ];
    protected $primaryKey = 'id';
    public static function getAttributeName() {
        return [
            'title_id' => trans('lacategory.title_code'),
            'subject_id'=>'Mã chuyên đề',
            'training_program_id' => trans('latraining.training_program'),
            'training_form' => trans('latraining.training_type'),
        ];
    }
    public static function checkSubjectExits($training_program_id, $subject_id, $title_id, $exclude_id = null){
        $query = self::query();
        $query->where('training_program_id', '=', $training_program_id);
        $query->where('subject_id', '=', $subject_id);
        $query->where('title_id','=',$title_id);
        if ($exclude_id) {
            $query->where('id','!=',$exclude_id);
        }
        return $query->exists();
    }
}
