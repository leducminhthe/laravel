<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingLevelCourseExport extends Model
{
    use Cachable;
    protected $table = 'el_rating_level_course_export';
    protected $fillable = [
        'course_rating_level_id',
        'level',
        'user_id',
        'user_type',
        'course_id',
        'course_type',
        'title',
        'content',
    ];
    protected $primaryKey = 'id';
}
