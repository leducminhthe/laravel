<?php

namespace Modules\Certificate\Entities;

use App\Models\BaseModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CertificateDesign extends BaseModel
{
	use Cachable;
    protected $table = "el_certificate_design";
    protected $table_name = 'Chứng chỉ thiết kế';
    protected $primaryKey = 'id';
    protected $fillable = [
        'certificate_id',
        'name',
        'type',
        'pleft',
        'ptop',
        'status',
        'align',
        'font_size',
        'color',
        'value',
    ];
}
