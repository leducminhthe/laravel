<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingCourseCategory extends Model
{
    use Cachable;
    protected $table = 'el_rating_course_category';
    protected $fillable = [
        'rating_course_id',
        'category_id',
        'category_name',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'rating_course_id' => 'Đánh giá khóa học',
            'category_id' => trans('lamenu.category'),
            'category_name' => trans('laother.category_name'),
        ];
    }

    public function questions()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingCourseQuestion', 'course_category_id');
    }
}
