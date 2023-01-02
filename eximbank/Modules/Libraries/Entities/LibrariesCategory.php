<?php

namespace Modules\Libraries\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ChangeLogs;

/**
 * Modules\Libraries\Entities\LibrariesCategory
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent_id
 * @property int $type
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesCategory whereUpdatedBy($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Libraries\Entities\Libraries[] $library
 * @property-read int|null $library_count
 */
class LibrariesCategory extends BaseModel
{
    use ChangeLogs, Cachable;
    protected $table = 'el_libraries_category';
    protected $table_name = 'Danh mục thư viện';
    protected $fillable = [
        'name',
        'parent_id',
        'type',
        'created_by',
        'updated_by',
        'bg_mobile',
    ];
    protected $primaryKey = 'id';
    protected $casts = ['parent_id' => 'integer', ];
    public static function getAttributeName() {
        return [
            'name' => trans('laother.category_name'),
            'type' => 'Loại danh mục',
            'created_by' => trans('laother.creator'),
            'updated_by' => trans('laother.editor'),
        ];
    }

    public static function getLibrariesParent($type, $exclude_id = 0, $parent_id = null, $prefix = '', &$result = []) {
        $query = self::query();
        $query->where('type', '=', $type);
        $query->where('parent_id', '=', $parent_id);
        $rows = $query->get();

        foreach ($rows as $row) {
            if ($row->id == $exclude_id) continue;
            $result[] = ['id' => $row->id, 'name' => $prefix.' '. $row->name];

            self::getLibrariesParent($type, $exclude_id, $row->id, $prefix.'--', $result);
        }

        return $result;
    }

    public static function getCategory($type = null)
    {
        $query = self::query();
        if ($type){
            $query->where('type', '=', $type);
        }

        return $query->get();
    }

    public function library()
    {
        return $this->hasMany('Modules\Libraries\Entities\Libraries','category_id', 'id');
    }

    public function cateChild($parent_id, $type)
    {
        $query = self::query();
        $query->where('parent_id', '=', $parent_id);
        $query->where('type', '=', $type);
        $rows = $query->get();
        return $rows;
    }

    public static function getTreeParentUnit($id, &$result = []) {
        $records = self::select('id','name','parent_id')->where('id',$id)->get();
        foreach ($records as $key => $record) {
            $result[] = $record;
            if ($record->parent_id) {
                self::getTreeParentUnit($record->parent_id, $result);
            }
        }

        return $result;
    }
}
