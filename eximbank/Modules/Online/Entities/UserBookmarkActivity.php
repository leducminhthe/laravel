<?php

namespace Modules\Online\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class UserBookmarkActivity extends Model
{
    use Cachable;
    protected $table = 'el_user_bookmark_activity';
    protected $table_name = 'HV đánh dấu hoạt động Khóa học online';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'activity_id',
        'course_id',
        'status',
    ];
}
