<?php

namespace Modules\Online\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OnlineRatingLevelObject extends Model
{
    use Cachable;
    protected $table = 'el_online_rating_level_object';
    protected $table_name = 'Đối tượng Mô hình kirkpatrick Khóa học online';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'online_rating_level_id',
        'object_type',
        'num_user',
        'user_id',
        'rating_user_id',
        'start_date',
        'end_date',
        'time_type',
        'num_date',
        'object_view_rating',
        'user_completed',
    ];
}
