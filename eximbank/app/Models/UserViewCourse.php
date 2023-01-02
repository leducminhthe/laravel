<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class UserViewCourse extends Model
{
    use Cachable;
    protected $table = 'el_user_view_course';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'course_type',
        'course_id',
        'time_view',
    ];
}
