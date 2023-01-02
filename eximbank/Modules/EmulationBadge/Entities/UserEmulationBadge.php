<?php

namespace Modules\EmulationBadge\Entities;

use App\Models\BaseModel;
use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class UserEmulationBadge extends Model
{
	protected $table = "user_emulation_badge";
    protected $fillable = [
        "emulation_badge_id",
        "course_id",
        "user_id",
        "armorial_id",
    ];
}
