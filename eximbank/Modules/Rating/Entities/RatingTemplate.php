<?php

namespace Modules\Rating\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingTemplate extends BaseModel
{
    use Cachable;
    protected $table = 'el_rating_template';
    protected $fillable = [
        'code',
        'name',
        'description',
        'created_by',
        'updated_by',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'code' => 'Mã mẫu',
            'name' => 'Tên mẫu',
            'created_by' => trans('laother.creator'),
            'updated_by' => trans('laother.editor'),
        ];
    }

    public static function removeTemplate($id)
    {
        $query = self::query();
        return $query->select(['id', 'name'])
            ->where('question_id', '=', $id)
            ->get();

    }

    public function category()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingCategory','template_id');
    }
}
