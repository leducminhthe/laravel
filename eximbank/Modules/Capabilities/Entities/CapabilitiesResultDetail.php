<?php

namespace Modules\Capabilities\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CapabilitiesResultDetail extends Model
{
//    use Cachable;
    protected $table = 'el_capabilities_result_detail';
    public $timestamps = false;
    protected $fillable = [];

    public static function checkExists($subject_id, $result_id, $user_id) {
        $query = self::query();
        return $query->where('result_id', '=', $result_id)
            ->where('subject_id', '=', $subject_id)
            ->where('user_id', '=', $user_id)
            ->first();
    }
}
