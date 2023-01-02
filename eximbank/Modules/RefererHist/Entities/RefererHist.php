<?php

namespace Modules\RefererHist\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class RefererHist extends Model
{
    use Cachable;
    public $table = 'el_referer_hist';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'referer',
        'point',
    ];

    public static function getAttributeName() {
        return [
            'user_id' => 'Mã nhân viên',
            'referer' => 'Mã giới thiệu',
            'point' => 'Điểm giới thiệu',
        ];
    }
    public static function existsRefer($referer)
    {
        $user_id = profile()->user_id;
        return RefererHist::where('referer','=',$referer)->where('user_id','=',$user_id)->exists();
    }
}
