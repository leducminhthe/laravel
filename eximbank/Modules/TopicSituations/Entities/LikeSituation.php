<?php

namespace Modules\TopicSituations\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class LikeSituation extends Model
{
    use Cachable;
    protected $table = 'el_like_situation';
    protected $table_name = 'Like chuyên đề tình huống';
    protected $fillable = [
        'user_id',
        'situation_id',
    ];
    protected $primaryKey = 'id';
}
