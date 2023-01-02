<?php

namespace Modules\PermissionApproved\Entities;

use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ApprovedObjectLevel extends Model
{
    use Cachable;
    protected $table = 'el_approved_object_level';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
    ];
}
