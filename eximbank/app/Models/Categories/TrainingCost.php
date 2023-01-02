<?php

namespace App\Models\Categories;

use App\Models\BaseModel;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Categories\TrainingCost
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingCost whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingCost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingCost extends BaseModel
{
    use ChangeLogs, Cachable;

    protected $table = 'el_training_cost';
    protected $table_name = "Chi phí đào tạo";
    protected $fillable = [
        'name',
        'type',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => 'Tên chi phí đào tạo',
            'type' => 'Loại chi phí'
        ];
    }
}
