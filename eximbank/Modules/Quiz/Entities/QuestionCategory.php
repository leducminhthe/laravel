<?php

namespace Modules\Quiz\Entities;

use App\Models\BaseModel;
use App\Models\Permission;
use App\Scopes\DraftScope;
use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Quiz\Entities\QuestionCategory
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property int $status
 * @property int|null $unit_id
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Quiz\Entities\QuestionCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class QuestionCategory extends BaseModel
{
    use ChangeLogs, Cachable;
    protected $table = 'el_question_category';
    protected $table_name = 'Danh mục câu hỏi';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'parent_id', 'level'];
    protected $casts = [ 'parent_id' => 'integer', ];
    public static function getAttributeName() {
        return [
            'name' => trans('laother.category_name'),
        ];
    }

    public static function getCategories($parent = null, $manager_ids = [], $exclude = null, $prefix = '', &$result = []) {

        $query = self::query();
        $query->where('parent_id', '=', $parent);
        $query->where('status', '=', 1);
        $rows = $query->get();
        foreach ($rows as $row) {
            $result[] = (object) [
                'id' => $row->id,
                'name' => $prefix . $row->name
            ];
            self::getCategories($row->id, $manager_ids, $exclude, $prefix . '-- ',$result);
        }
        return $result;
    }

    public static function countQuestion($cat_id, $difficulty = null) {
        $query = Question::query();
        $query->where('category_id', '=', $cat_id);
        $query->where('status', '=', 1);

        if($difficulty){
            $query->where('difficulty', '=', $difficulty);
        }
        
        return $query->count('id');
    }

    public static function getCategoryUnit($units = []) {
        $query = QuestionCategory::query();
        $query->where('status', '=', 1);

        return $query->pluck('id')->toArray();
    }
}
