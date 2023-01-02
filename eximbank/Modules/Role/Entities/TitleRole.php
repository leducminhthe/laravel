<?php

namespace Modules\Role\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class TitleRole extends Model
{
    use Cachable;
    public $table = 'el_role_title';
    protected $table_name = 'Vai trò theo chức danh';
    public $incrementing = false;
    protected $fillable = [
        'title_id',
        'role_id',
    ];
}
