<?php

namespace Modules\Rating\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingCategory extends Model
{
    use Cachable;
    protected $table = 'el_rating_category';
    protected $fillable = [
        'name',
        'template_id',
        'rating_teacher',
    ];
    protected $primaryKey = 'id';

    public static function getAttributeName() {
        return [
            'name' => trans('laother.category_name'),
            'template_id' => 'Mẫu',
        ];
    }

    public static function getCategoryTemplate($template_id){
        $query = self::query();
        return $query->select(['id', 'name'])
            ->where('template_id', '=', $template_id)
            ->get();
    }

    public function questions()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingQuestion','category_id');
    }
}
