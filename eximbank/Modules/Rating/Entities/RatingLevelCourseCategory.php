<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingLevelCourseCategory extends Model
{
    use Cachable;
    protected $table = 'el_rating_level_course_category';
    protected $fillable = [
        'rating_level_course_id',
        'category_id',
        'category_name',
    ];
    protected $primaryKey = 'id';

    public function questions()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingLevelCourseQuestion', 'course_category_id');
    }
}
