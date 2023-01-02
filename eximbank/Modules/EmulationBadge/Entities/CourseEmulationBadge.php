<?php

namespace Modules\EmulationBadge\Entities;

use App\Models\BaseModel;
use App\Models\CacheModel;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CourseEmulationBadge extends Model
{
	protected $table = "course_emulation_badge";
    protected $fillable = [
        "emulation_badge_id",
        "course_id",
        "course_type",
    ];
}
