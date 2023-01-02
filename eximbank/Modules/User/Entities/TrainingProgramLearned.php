<?php

namespace Modules\User\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\User\Entities\ManagerLevel
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_manager_id
 * @property int $level
 * @property string $start_date
 * @property string|null $end_date
 * @property int $approve
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereApprove($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\User\Entities\ManagerLevel whereUserManagerId($value)
 * @mixin \Eloquent
 * @property string|null $training_program
 * @property string|null $time
 * @property string|null $note
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProgramLearned whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProgramLearned whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrainingProgramLearned whereTrainingProgram($value)
 */
class TrainingProgramLearned extends Model
{
    use Cachable;
    protected $table = 'el_training_program_learned';
    protected $table_name = 'Chủ đề đã học';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'training_program',
        'time',
        'note'
    ];

    public static function getAttributeName() {
        return [
            'user_id' => trans('lamenu.user'),
        ];
    }
}
