<?php

namespace Modules\Suggest\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class Suggest extends BaseModel
{
    use Cachable;
    protected $table = 'el_suggest';
    protected $table_name = 'Góp ý';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'name',
        'content',
    ];
    public static function getAttributeName() {
        return [
            'user_id' => trans('lamenu.user'),
            'name' => 'Tên đề xuất',
            'content' => 'Nội dung đề xuất',
        ];
    }
}
