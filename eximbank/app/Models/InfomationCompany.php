<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class InfomationCompany extends Model
{
    // use Cachable;
    protected $table = 'el_infomation_company';
    protected $table_name = "Thông tin công ty";
    protected $primaryKey = 'id';
    protected $fillable = [
        'content',
        'title',
    ];

    public static function getAttributeName() {
        return [
            'content' => trans("latraining.content"),
            'title' => 'Tiêu đề',
        ];
    }
}
