<?php

namespace Modules\TopicSituations\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class Topic extends BaseModel
{
    use Cachable;
    protected $table = 'el_topic';
    protected $table_name = 'Xử lý tình huống';
    protected $fillable = [
        'name',
        'code',
        'image',
        'created_by',
        'updated_by',
        'unit_by',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên xử lý tình huống',
            'code' => 'Mã xử lý tình huống',
            'iamge' => 'ảnh',
        ];
    }
}
