<?php

namespace App\Models\Categories;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Categories\TrainingProgram
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingProgram query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingProgram whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingProgram whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingProgram whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingProgram whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingProgram whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories\TrainingProgram whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrainingProgram extends BaseModel
{
    use Cachable;
    protected $table = 'el_training_program';
    protected $table_name = "Chủ đề";
    protected $fillable = [
        'code',
        'name',
        'status',
        'order',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => trans('backend.topic_training_program_code'),
            'name' => trans('backend.topic_training_program'),
            'status' => trans('latraining.status'),
        ];
    }
}
