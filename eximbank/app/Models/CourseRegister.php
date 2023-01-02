<?php

namespace App\Models;

use App\Traits\OverrideTablePrefix;
use Illuminate\Database\Eloquent\Model;

class CourseRegister extends BaseModel
{
    use OverrideTablePrefix;
    protected $table = "v_course_register";

}
