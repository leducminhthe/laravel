<?php

namespace Modules\NewsOutside\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use App\Traits\ChangeLogs;
use GuzzleHttp\Client;
use Spatie\Permission\Traits\HasRoles;

class NewsOutsideCategory extends BaseModel
{
    use Cachable;
    protected $table = 'el_news_outside_category';
    protected $table_name = 'Danh mục tin tức chung';
    protected $fillable = [
        'icon',
        'name',
        'parent_id',
        'status',
        'sort',
        'stt_sort',
        'stt_sort_parent',
        'created_by',
        'updated_by'
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'icon' => 'Icon',
            'name' => trans('laother.category_name'),
            'parent_id'=>trans('labutton.parent_category'),
            'status'=>'Hiện trên trang chủ',
            'sort'=>'Sắp xếp',
            'stt_sort'=>'Số thứ tự sắp xếp',
            'stt_sort_parent'=>'Số thứ tự sắp xếp cấp cha',
            'created_by'=>trans('laother.creator'),
            'updated_by'=>trans('laother.editor')
        ];
    }

    public function child(){
        return $this->hasMany(NewsOutsideCategory::class, 'parent_id', 'id');
    }
}
