<?php

namespace Modules\Online\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\OnlineCourseUpload
 *
 * @property int $id
 * @property int $course_id
 * @property int $upload
 * @property int $user_id
 * @property int $num_star
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseUpload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseUpload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseUpload query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseUpload whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseUpload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseUpload whereUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseUpload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\Online\Entities\OnlineCourseUpload whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OnlineCourseUpload extends Model
{
    use Cachable;
    protected $table = 'el_online_course_upload';
    protected $table_name = 'Quản lý file Khóa học online';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'upload',
    ];

    public static function getAttributeName() {
        return [
            'course_id' => trans('lamenu.course'),
            'upload' => 'Quản lý file',
        ];
    }
}
