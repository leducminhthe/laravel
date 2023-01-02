<?php

namespace Modules\Offline\Entities;

use Illuminate\Database\Eloquent\Model;

class OfflineViewActivity extends Model
{
    protected $table = 'offline_view_activity';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'course_id', 'activity_id'];
}
