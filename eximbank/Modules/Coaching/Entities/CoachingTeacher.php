<?php

namespace Modules\Coaching\Entities;

use App\Models\Profile;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CoachingTeacher extends Model
{
    use Cachable;
    protected $table = "el_coaching_teacher";
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'image',
        'technique',
        'start_date',
        'end_date',
        'coaching_group_id',
        'number_coaching',
        'status',
        'full_class',
    ];

    public static function getAttributeName() {
        return [
            'user_id' => trans('lareport.teacher'),
            'image' => 'Hình',
            'technique' => 'Chuyên môn',
            'start_date' => trans('latraining.start_date'),
            'end_date' => trans('latraining.end_date'),
            'coaching_group_id' => 'Nhóm coaching',
            'number_coaching' => 'SL kèm cặp',
        ];
    }

    public function user()
    {
        return $this->belongsTo(Profile::class,'user_id','id');
    }

    public function coaching_group()
    {
        return $this->belongsTo(CoachingGroup::class, 'coaching_group_id', 'id');
    }
}
