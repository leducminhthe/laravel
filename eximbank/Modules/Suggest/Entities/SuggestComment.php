<?php

namespace Modules\Suggest\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class SuggestComment extends Model
{
    use Cachable;
    protected $table = 'el_suggest_comment';
    protected $table_name = 'Bình luận góp ý';
    protected $primaryKey = 'id';
    protected $fillable = [
        'suggest_id',
        'user_id',
        'content'
    ];

    public static function getAttributeName() {
        return [
            'user_id' => trans('lamenu.user'),
            'suggest_id' => 'Tên đề xuất',
            'content' => 'Nội dung bình luận',
        ];
    }
}
