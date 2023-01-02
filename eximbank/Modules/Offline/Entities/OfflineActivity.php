<?php

namespace Modules\Offline\Entities;

use App\Traits\ChangeLogs;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class OfflineActivity extends Model
{
    use ChangeLogs, Cachable;
    protected $table = 'offline_activity';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'name',
        'description',
        'icon'
    ];

    public function offline_activities() {
        return $this->hasMany(OfflineCourseActivity::class, 'activity_id', 'id');
    }
}
