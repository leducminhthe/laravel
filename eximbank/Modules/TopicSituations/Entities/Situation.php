<?php

namespace Modules\TopicSituations\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Situation extends Model
{
    use Cachable;
    protected $table = 'el_situation';
    protected $table_name = 'Chuyên đề tình huống';
    protected $fillable = [
        'name',
        'code',
        'description',
        'topic_id',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên',
            'code' => 'Mã',
            'description' => trans("latraining.description"),
        ];
    }
}
