<?php

namespace Modules\TrainingPlan\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class TrainingPlanDetailTypeCost extends BaseModel
{
    use Cachable;
    protected $table = 'el_training_plan_detail_type_cost';
    protected $table_name = 'Chi phí kế hoạch đào tạo năm';
    protected $fillable = [
        'training_plan_detail_id',
        'status',
        'cost_id',
        'training_plan_id',
    ];
    protected $primaryKey = 'id';
}
