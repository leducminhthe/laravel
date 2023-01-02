<?php

namespace Modules\TargetManager\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class TargetManagerGroup extends Model
{
    protected $table = 'el_target_manager_group';
    protected $fillable = [
        'target_manager_id',
        'title_id',
        'user_id',
    ];
    protected $primaryKey = 'id';
}
