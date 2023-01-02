<?php

namespace Modules\NewsOutside\Entities;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use App\Traits\ChangeLogs;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class NewsOutside extends BaseModel
{
    use Cachable;
    protected $table = 'el_news_outside';
    protected $table_name = 'Tin tức chung';
    protected $fillable = [
        'title',
        'content',
        'description',
        'views',
        'status',
        'image',
        'date_setup_icon',
        'number_setup',
        'category_id',
        'created_by',
        'updated_by',
        'user_view',
        'hot',
        'hot_public',
        'view_time',
        'type',
        'like_new'
    ];

    public static function getAttributeName() {
        return [
            'category_id' => 'Danh mục tin tức chung',
            'title' => 'Tiêu đề',
            'content' => trans("latraining.content"),
            'description' => 'Mô tả',
            'status'=>'Trang thái',
            'created_by' => trans('laother.creator'),
            'updated_by' => trans('laother.editor'),
            'type' => 'thể loại',
        ];
    }

    public static function updateItemViews($id){
        $model = NewsOutside::find($id);

        DB::table('el_news_outside')
            ->where('id',$id)
            ->update([
                'views' => $model->views + 1,
                'view_time' => date('Y-m-d H:i:s'),
            ]);
    }
}
