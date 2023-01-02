<?php

namespace Modules\Offline\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OfflineRatingLevelObjectColleague extends Model
{
    use Cachable;
    protected $table = 'el_offline_rating_level_object_colleague';
    protected $table_name = 'Đối tượng đồng nghiệp Mô Hình Kirkpatrick Khóa học tập trung';
    protected $primaryKey = 'id';
    protected $fillable = [
        'offline_rating_level_id',
        'user_id',
        'rating_user_id',
    ];
}
