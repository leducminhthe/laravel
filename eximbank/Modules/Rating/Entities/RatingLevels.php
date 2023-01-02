<?php

namespace Modules\Rating\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RatingLevels extends BaseModel
{
    use Cachable;
    protected $table = 'el_rating_levels';
    protected $table_name = 'Mô hình Kirkpatrick';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by',
        'unit_by',
    ];

    public function courses()
    {
        return $this->hasMany('Modules\Rating\Entities\RatingLevelsCourses','rating_levels_id');
    }
}
