<?php

namespace Modules\Libraries\Entities;

use App\Models\CacheModel;
use App\Models\Profile;
use App\Models\ProfileView;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Response;

/**
 * Modules\Libraries\Entities\LibrariesObject
 *
 * @property int $id
 * @property int $libraries_id
 * @property int $type
 * @property int|null $status
 * @property int|null $title_id
 * @property int|null $unit_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereLibrariesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Libraries\Entities\LibrariesObject whereUserId($value)
 * @mixin \Eloquent
 */
class LibrariesObject extends Model
{
    use Cachable;
    protected $table = 'el_libraries_object';
    protected $table_name = 'Đối tượng thư viện';
    protected $fillable = [
        'libraries_id',
        'type',
        'status',
        'unit_id',
        'title_id',
        'user_id',
    ];
    protected $primaryKey = 'id';

    public static function checkObjectUnit ($libraries_id, $unit_id, $type){
        $query = self::query();
        $query->where('unit_id', '=', $unit_id);
        $query->where('libraries_id', '=', $libraries_id);
        $query->where('type', '=', $type);
        return $query->exists();
    }
    public static function checkObjectTitle ($libraries_id, $title_id, $type){
        $query = self::query();
        $query->where('title_id', '=', $title_id);
        $query->where('libraries_id', '=', $libraries_id);
        $query->where('type', '=', $type);
        return $query->exists();
    }

    public static function getStatus($user_id, $libraries_id, $type){
        $profile = ProfileView::where('user_id', '=', $user_id)->first(['user_id', 'unit_id', 'title_id']);

        $status = LibrariesObject::where(function($sub) use ($profile){
            $sub->orWhere('user_id', '=', $profile->user_id)
            ->orWhere('title_id', '=', $profile->title_id)
            ->orWhere('unit_id', '=', $profile->unit_id);
        })
        ->where('libraries_id', '=', $libraries_id)
        ->where('type', '=', $type)
        ->first();

        return $status;
    }

    public static function getAttributeName() {
        return [
            'unit_id'=> trans('lamenu.unit'),
            'title_id' => trans('latraining.title'),
        ];
    }
}
