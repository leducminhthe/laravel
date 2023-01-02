<?php

namespace Modules\Forum\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ForumCategory extends BaseModel
{
    use Cachable;
    protected $table = 'el_forum_category';
    protected $table_name = 'Danh mục diễn đàn';
    protected $fillable = [
        'icon',
        'name',
        'status',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'icon' => 'Icon',
            'name' => trans('laother.category_name'),
            'status'=> trans("latraining.status"),
        ];
    }

    public function topic()
    {
        return $this->hasMany('Modules\Forum\Entities\Forum','category_id');
    }
}
