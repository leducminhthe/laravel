<?php

namespace App\Models;

use App\Scopes\CompanyScope;
use App\Scopes\DraftScope;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfileView;
use App\Models\Permission;

/**
 * App\Models\WarehouseFolder
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseFolder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseFolder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseFolder query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseFolder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseFolder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseFolder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseFolder whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseFolder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WarehouseFolder whereUserId($value)
 * @mixin \Eloquent
 * @property string $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Warehouse[] $files
 * @property-read int|null $files_count
 * @method static \Illuminate\Database\Eloquent\Builder|WarehouseFolder whereType($value)
 */
class WarehouseFolder extends BaseModel
{
    use Cachable;
    protected $table = 'el_warehouse_folder';
    protected $table_name = "Thư mục quản lý tệp tin";
    protected $primaryKey = 'id';

    public function files() {
        return $this->hasMany('App\Models\Warehouse', 'folder_id', 'id');
    }

    public function childs() {
        return $this->hasMany('App\Models\WarehouseFolder', 'parent_id', 'id');
    }

    public static function getDirectories($path, $type = 'image') {
        $getUnitProfile = session('user_unit') ?? Profile::getUnitId() ?? null;
        $parent_id = (int) $path > 0 ? $path : null;
        
        $query = WarehouseFolder::where('parent_id', '=', $parent_id)
            ->where('type', '=', $type);

        if ($query->exists()) {
            $rows = $query->get();
            $result = [];

            foreach ($rows as $row) {
                if(!Permission::isAdmin() && !empty($row->unit_by) && ($row->unit_by != $getUnitProfile)) {
                    continue;
                }
                $result[] = (object) [
                    'id' => $row->id,
                    'name' => $row->name,
                    'unit_by' => $row->unit_by,
                    'url' => '',
                    'size' => '',
                    'updated' => strtotime($row->updated_at),
                    'path' => $row->id,
                    'time' => $row->created_at,
                    'type' => 'folder',
                    'icon' => 'fa-folder-o',
                    'thumb' => asset('styles/file-manager/images/folder.png'),
                    'is_file' => false,
                    'child' => ($row->files->count() + $row->childs->count()),
                ];
            }

            return $result;
        }

        return [];
    }

    public static function checkExists($folder_name, $parent_folder = null) {
        $query = self::query();
        $query->where('name', '=', $folder_name);
        $query->where('parent_id', '=', $parent_folder);
        return $query->exists();
    }

    public static function getParent($folder_id) {
        $query = WarehouseFolder::query();
        $query->where('id', '=', $folder_id);
        if ($query->exists()) {
            $parent_id = $query->first()->parent_id;
            if (empty($parent_id)) {
                return -1;
            }

            return $parent_id;
        }
        return '';
    }
}
