<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Feedback
 *
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $position
 * @property int $star
 * @property string $content
 * @property int $created_by
 * @property int $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback whereStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Feedback whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class Feedback extends Model
{
    use Cachable;
    protected $table = 'el_feedback';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'content',
        'position',
        'star',
        'created_by',
        'image',
    ];
    public static function getAttributeName() {
        return [
            'name' => 'Tên',
            'content' => 'Nội dung phản hồi',
            'position' => 'Chức vụ',
            'star' => 'Số sao',
            'created_by' => 'Người tạo',
            'image' => trans("latraining.picture"),
        ];
    }
}
