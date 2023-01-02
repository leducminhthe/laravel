<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingLevelCourse extends Model
{
    use Cachable;
    protected $table = 'el_rating_level_course';
    protected $fillable = [
        'course_rating_level_id',
        'course_rating_level_object_id',
        'level',
        'user_id',
        'user_type',
        'course_id',
        'course_type',
        'rating_user',
        'user_update',
        'send',
        'template_id',
    ];
    protected $primaryKey = 'id';
}
