<?php

namespace Modules\EmulationBadge\Entities;

use App\Models\BaseModel;
use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class EmulationBadge extends BaseModel
{
	protected $table = "emulation_badge";
    protected $fillable = [
        "code",
        "name",
        "description",
        "start_time",
        "end_time",
        "status",
    ];
}
