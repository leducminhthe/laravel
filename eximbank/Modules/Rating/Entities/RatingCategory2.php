<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingCategory2 extends Model
{
    use Cachable;
    protected $table = 'el_rating_category2';
    protected $fillable = [
        'course_rating_level_id',
        'course_rating_level_object_id',
        'course_id',
        'course_type',
        'name',
        'template_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans('laother.category_name'),
            'template_id' => 'Mẫu',
        ];
    }

    public function questions()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingQuestion2','category_id');
    }
}
