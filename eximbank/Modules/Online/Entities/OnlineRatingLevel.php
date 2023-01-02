<?php

namespace Modules\Online\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OnlineRatingLevel extends Model
{
    use Cachable;
    protected $table = 'el_online_rating_level';
    protected $table_name = 'Mô hình kirkpatrick Khóa học online';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
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
