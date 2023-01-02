<?php

namespace Modules\PlanApp\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class PlanAppTemplate extends BaseModel
{
    use Cachable;
    protected $table = 'el_plan_app_template';
    protected $table_name = 'Mẫu đánh giá Kế hoạch ứng dụng';
    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên mẫu đánh giá',
        ];
    }
}
