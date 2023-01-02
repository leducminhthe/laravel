<?php

namespace Modules\News\Entities;

use App\Models\CacheModel;
use App\Models\Profile;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use Response;
use App\Models\Categories\Unit;

/**
 * Modules\News\Entities\NewsObject
 *
 * @property int $id
 * @property int $new_id
 * @property int|null $status
 * @property int|null $title_id
 * @property int|null $unit_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject whereNewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject whereTitleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NewsObject whereUserId($value)
 * @mixin \Eloquent
 */
class NewsObject extends Model
{
    use Cachable;
    protected $table = 'el_news_object';
    protected $table_name = 'Đối tượng tin tức';
    protected $fillable = [
        'new_id',
        'status',
        'unit_id',
        'title_id',
        'user_id',
    ];
    protected $primaryKey = 'id';

    public static function checkObjectUnit ($new_id, $unit_id){
        $query = self::query();
        $query->where('unit_id', '=', $unit_id);
        $query->where('new_id', '=', $new_id);
        return $query->exists();
    }
    public static function checkObjectTitle ($new_id, $title_id){
        $query = self::query();
        $query->where('title_id', '=', $title_id);
        $query->where('new_id', '=', $new_id);
        return $query->exists();
    }

    public static function getStatus($user_id, $new_id){
        $profile = Profile::leftJoin('el_titles AS b', 'b.code', '=', 'title_code')
            ->leftJoin('el_unit AS c', 'c.code', '=', 'unit_code')
            ->where('user_id', '=', $user_id)
            ->first(['user_id', 'c.id as unit_id', 'b.id as title_id']);

        $status = LibrariesObject::where('user_id', '=', $profile->user_id)
            ->orWhere('title_id', '=', $profile->title_id)
            ->orWhere('unit_id', '=', $profile->unit_id)
            ->where('new_id', '=', $new_id)
            ->first();
        return $status;
    }

    public static function checkUnitNew($id, $user_unit_id) {
        $query = self::query();
        $query->where('new_id', $id);
        $query->whereNotNull('unit_id');
        $get_objects_new = $query->get();
        $check_unit = 0;
        if ( !$get_objects_new->isEmpty() ) {
            foreach ($get_objects_new as $get_object_new) {
                $unit_code = Unit::find($get_object_new->unit_id);
                $get_array_childs = Unit::getArrayChild($unit_code->code);
                if( in_array($user_unit_id, $get_array_childs) || $user_unit_id == $get_object_new->unit_id) {
                    $check_unit = 1;
                }
            }
        }
        return $check_unit;
    }

    public static function checkUnitNewCate($unit_id, $user_unit_id) {
        $check_unit = 0;
        $unit_code = Unit::find($unit_id,['code']);
        $get_array_childs = Unit::getArrayChild($unit_code->code);
        if( in_array($user_unit_id, $get_array_childs) || $user_unit_id == $unit_id) {
            $check_unit = 1;
        }
        return $check_unit;
    }
}
