<?php

namespace Modules\Online\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Online\Entities\Xapi
 *
 * @property int $id
 * @property string $origin_path
 * @property string|null $unzip_path
 * @property string|null $index_file
 * @property string|null $error
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Online\Entities\OnlineCourseActivityXapi[] $course_activities
 * @property-read int|null $course_activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi whereIndexFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi whereOriginPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi whereUnzipPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Xapi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Xapi extends Model
{
    protected $table = 'el_xapi';
    protected $fillable = [
        'origin_path',
        'unzip_path',
        'index_file',
        'status',
        'error',
    ];

    public function course_activities() {
        return $this->hasMany(OnlineCourseActivityXapi::class, 'path', 'origin_path');
    }
}
