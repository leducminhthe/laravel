<?php

namespace App\Http\Controllers\Api\CourseEducatePlanTeacher;

use App\Http\Requests\CourseEducatePlanTeacher\CourseEducatePlanTeacherRequest;
use App\Models\Api\CourseEducatePlanTeacherModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CourseEducatePlanTeacherController extends Controller
{
    protected $model = CourseEducatePlanTeacherModel::class;

    protected $request = CourseEducatePlanTeacherRequest::class;
}
