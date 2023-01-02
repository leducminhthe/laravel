<?php

namespace Modules\RefererHist\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;

/**
 * Class RefererRegisterCourse
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @mixin \Eloquent
 * @package App
 */
class RefererRegisterCourse extends Model
{
    use Cachable;
    public $table = 'el_referer_register_course';
    protected $primaryKey = 'id';
    protected $fillable = [
        'course_id',
        'type',
        'user_id',
        'referer',
        'state',
    ];

    public static function getAttributeName() {
        return [
            'course_id' => trans('lacourse.course_code'),
            'type' => 'Loại khóa học',
            'user_id' => 'Mã user',
            'referer' => 'Mã người giới thiệu',
            'state' => trans("latraining.status"),
        ];
    }
    public static function saveReferRegisterOfflineCourse($course_id,$referer)
    {
        $user_refer = Profile::where('id_code', '=', $referer)->value('user_id');
        $model = new RefererRegisterCourse();
        $model->course_id = $course_id;
        $model->type = 2;
        $model->user_id = profile()->user_id;
        $model->referer = $user_refer;
        $model->save();
    }
    public static function saveReferRegisterOnlineCourse($course_id,$referer)
    {
        $user_refer = Profile::where('id_code', '=', $referer)->value('user_id');
        $model = new RefererRegisterCourse();
        $model->course_id = $course_id;
        $model->type = 1;
        $model->user_id = profile()->user_id;
        $model->referer = $user_refer;
        $model->save();
    }
}
