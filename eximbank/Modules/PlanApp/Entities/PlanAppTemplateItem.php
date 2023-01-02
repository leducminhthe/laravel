<?php

namespace Modules\PlanApp\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class PlanAppTemplateItem extends Model
{
    use Cachable;
    protected $table = 'el_plan_app_template_item';
    protected $fillable = [
        'name',
        'data_type',
        'sort',
        'cate_id',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên tiêu chí',
            'data_type' => 'kiểu dữ liệu',
            'sort' => 'thứ tự',
            'cate_id' => 'Mã nhóm đề mục',
        ];
    }
}
