<?php

namespace Modules\Rating\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CourseRatingLevel extends BaseModel
{
    use Cachable;
    protected $table = 'el_course_rating_level';
    protected $primaryKey = 'id';
    protected $fillable = [
        'rating_levels_id',
        'level',
        'rating_template_id',
        'rating_name',
        'created_by',
        'updated_by',
        'unit_by',
        'object_rating',
    ];

    public static function getAttributeName(){
        return [
            'level' => 'Cấp độ đánh giá',
            'rating_template_id' => 'Mẫu đánh giá',
            'rating_name' => 'Tên đánh giá',
        ];
    }
}
