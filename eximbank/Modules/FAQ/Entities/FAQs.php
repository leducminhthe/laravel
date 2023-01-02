<?php

namespace Modules\FAQ\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class FAQs extends BaseModel
{
    use Cachable;
    protected $table = 'el_faq';
    protected $table_name = 'Câu hỏi thường gặp';
    protected $fillable = [
        'name',
        'content',
    ];
    public static function getAttributeName() {
        return [
            'name' => 'Tiêu đề',
            'content' => trans("latraining.content"),
        ];
    }
    protected $primaryKey = 'id';
}
