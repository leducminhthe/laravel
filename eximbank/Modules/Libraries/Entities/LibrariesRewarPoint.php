<?php

namespace Modules\Libraries\Entities;

use App\Models\CacheModel;
use App\Models\Profile;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Response;

class LibrariesRewarPoint extends Model
{
    use Cachable;
    protected $table = 'el_libraries_reward_point';
    protected $table_name = 'Điểm thưởng đánh giá sao thư viện';
    protected $primaryKey = 'id';
    protected $fillable = [
        'score',
        'libraries_id',
        'rating',
        'setting',
    ];
}
