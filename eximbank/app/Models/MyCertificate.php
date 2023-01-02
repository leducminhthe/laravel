<?php

namespace App\Models;

use Carbon\Carbon;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use http\Client\Request;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class MyCertificate extends Model
{
    // use Cachable;
    protected $table = 'el_my_certificate';
    protected $table_name = "Chứng chỉ của tôi";
    protected $fillable = [
        'user_id',
        'name_certificate',
        'name_school',
        'rank',
        'time_start',
        'date_license',
        'score',
        'result',
        'note',
        'certificate',
    ];

    public static function getAttributeName() {
        return [
            'name_certificate' => 'Tên chứng chỉ',
            'name_school' => 'Tên trường',
            'time_start' => 'Ngày bắt đầu học',
            'date_license' => 'Ngày cấp chứng chỉ',
            'score' => trans('latraining.score'),
            'result' => trans('latraining.result'),
            'certificate' => 'Chứng chỉ',
        ];
    }
}
