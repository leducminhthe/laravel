<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineActivityScormAttemptData extends Model
{
    public $timestamps = false;

    protected $table = 'offline_activity_scorm_attempt_data';
    protected $fillable = [
        'attempt_id',
        'var_name',
        'var_value',
    ];

    public function attempt() {
        return $this->hasOne(OfflineActivityScormAttempt::class, 'id', 'attempt_id');
    }
}
