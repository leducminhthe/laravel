<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class CourseTabEdit extends Model
{
    use Cachable;
    protected $table='el_course_tab_edit';
    protected $fillable = [
        'course_id',
        'course_type',
        'tab_edit',
    ];
}
