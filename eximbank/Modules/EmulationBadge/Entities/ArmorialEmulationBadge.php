<?php

namespace Modules\EmulationBadge\Entities;

use App\Models\BaseModel;
use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ArmorialEmulationBadge extends Model
{
	protected $table = "armorial_emulation_badge";
    protected $fillable = [
        "emulation_badge_id",
        "level",
        "image",
        "type",
    ];
}
