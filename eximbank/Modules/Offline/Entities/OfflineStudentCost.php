<?php

namespace Modules\Offline\Entities;

use App\Models\CacheModel;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Offline\Entities\OfflineStudentCost
 *
 * @property int $id
 * @property int $register_id
 * @property int $cost_id
 * @property int $cost
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost whereCostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost whereRegisterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Offline\Entities\OfflineStudentCost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OfflineStudentCost extends Model
{
    use ChangeLogs, Cachable;
    protected $table = 'el_offline_student_cost';
    protected $table_name = 'Chi phí học viên Khóa học tập trung';
    protected $fillable = [
        'register_id',
        'cost_id',
        'cost',
        'note',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'register_id' => 'Mã học viên',
            'cost_id' => 'Chi phí học viên',
            'cost' => 'Chi phí khác',
            'note' =>trans('latraining.note'),
        ];
    }

    public static function checkExists($register_id, $cost_id){
        $query = self::query();
        $query->where('cost_id', '=', $cost_id);
        $query->where('register_id', '=', $register_id);
        return $query->exists();
    }

    public static function getStudent($course_id)
    {
        $query = self::query();
        $query->select([
            'b.id',
            'b.user_id',
            'c.code as profile_code',
            'c.firstname as profile_firstname',
            'c.lastname as profile_lastname',
            'c.title_name',
            'c.parent_unit_name as parent_name',
            'c.unit_name',
            'c.title_id',
            'f.commit_date',
            'f.commit_amount',
            'f.exemption_amount',
            'f.cost_student',
            'f.course_cost',
            'f.coefficient',
            'f.calculator'
        ])->disableCache();
        $query->from('el_offline_register AS b');
        $query->leftJoin('el_profile_view AS c', 'c.user_id', '=', 'b.user_id');
        $query->leftJoin('el_indemnify AS f',function ($join){
            $join->on('f.user_id','=','c.user_id');
            $join->on('f.course_id','=','b.course_id');
        });
        $query->where('b.course_id', '=', $course_id);
        $query->where('b.status', '=', 1);
        return $query->get();
    }

    public static function getTotalActualAmount($course_id)
    {
        $total = 0;
        $course_costs = OfflineCourseCost::select('actual_amount')->where('course_id', '=', $course_id)->get();
        foreach($course_costs as $item){
            $total += $item->actual_amount;
        }

        return $total;
    }

    public static function getTotalPlanAmount($id)
    {
        $total = 0;
        $course_costs = OfflineCourseCost::select('plan_amount')->where('course_id', '=', $id)->get();
        foreach($course_costs as $item){
            $total += $item->plan_amount;
        }

        return $total;
    }

    public static function getRegister($regid)
    {
        $query = self::query();
        $query->select(['b.*', 'c.firstname as profile_firstname', 'c.lastname as profile_lastname']);
        $query->from('el_offline_register AS b');
        $query->leftJoin('el_profile AS c', 'c.user_id', '=', 'b.user_id');
        $query->where('b.id', '=', $regid);
        return $query->first();
    }

    public static function getTotalStudentCost($id)
    {
        $total = 0;
        $costs = OfflineStudentCost::select('cost')->where('register_id', '=', $id)->get();
        foreach($costs as $item){
            $total += $item->cost;
        }

        return $total;
    }
}
