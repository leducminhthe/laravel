<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CourseRatingLevelObjectColleague extends Model
{
    use Cachable;
    protected $table = 'el_course_rating_level_object_colleague';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_rating_level_id',
        'user_id',
        'rating_user_id',
        'rating_template_id',
    ];
}
