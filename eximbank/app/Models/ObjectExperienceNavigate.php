<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categories\Unit;

class ObjectExperienceNavigate extends Model
{
    protected $table = 'el_object_experience_navigate';
    protected $primaryKey = 'id';
    protected $fillable = [
        'experience_navigate_id',
        'unit_id',
        'title_id',
    ];

    public static function checkUnit($unit_id, $user_unit_id) {
        $check_unit = 0;
        $unit_code = Unit::find($unit_id,['code']);
        $get_array_childs = Unit::getArrayChild($unit_code->code);
        if( in_array($user_unit_id, $get_array_childs) || $user_unit_id == $unit_id) {
            $check_unit = 1;
        }
        return $check_unit;
    }
}

