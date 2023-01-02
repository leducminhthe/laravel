<?php

namespace Modules\Certificate\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class Certificate extends BaseModel
{
    use Cachable;
    protected $table = "el_certificate";
    protected $table_name = 'Chứng chỉ';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'image',
        'user',
        'position',
        'signature',
        'logo',
        'location',
		'type',
    ];

    public static function getAttributeName() {
        return [
            'code' => 'Mã chứng chỉ',
            'name' => trans('laprofile.certificate_name'),
            'image' => 'Ảnh chứng chỉ',
            'user' => 'Họ tên người đại diện',
            'position' => 'Chức vụ người đại diện',
            'signature' => 'Chữ ký người đại diện',
            'logo' => 'Logo',
            'location' => 'Vị trí',
			'type' => 'Loại',
        ];
    }
}
