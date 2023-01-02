<?php

namespace App\Http\Controllers\Api\CoursePlanTeacher;

use App\Http\Requests\CoursePlanTeacher\CoursePlanTeacherRequest;
use App\Models\Api\CoursePlanTeacherModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CoursePlanTeacherController extends Controller
{
    protected $model = CoursePlanTeacherModel::class;

    protected $request = CoursePlanTeacherRequest::class;
}
