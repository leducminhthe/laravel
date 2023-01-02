<?php

namespace Modules\DailyTraining\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class DailyTrainingCategory extends BaseModel
{
    use Cachable;
    protected $table = 'el_daily_training_category';
    protected $table_name = 'Danh mục đào tạo video';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'status_video',
    ];

    public static function getAttributeName() {
        return [
            'name' => trans('laother.category_name'),
            'status_video' => 'Trạng thái video',
        ];
    }
}
