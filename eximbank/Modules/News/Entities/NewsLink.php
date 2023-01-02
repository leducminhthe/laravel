<?php

namespace Modules\News\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class NewsLink extends BaseModel
{
    use Cachable;
    protected $table = 'el_news_link';
    protected $table_name = 'Link tin tức';
    protected $primaryKey = 'id';
    protected $fillable = [
        'news_id',
        'title',
        'link',
        'type',
        'created_by',
        'updated_by',
        'unit_by',
    ];

}
