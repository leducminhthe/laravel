<?php

namespace Modules\Online\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OnlineRatingLevelObjectColleague extends Model
{
    use Cachable;
    protected $table = 'el_online_rating_level_object_colleague';
    protected $table_name = 'Đối tượng đồng nghiệp Mô hình kirkpatrick Khóa học online';
    protected $primaryKey = 'id';
    protected $fillable = [
        'online_rating_level_id',
        'user_id',
        'rating_user_id',
    ];
}
