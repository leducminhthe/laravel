<?php

namespace App\Http\Controllers\Api\CourseEducatePlanSchedule;

use App\Http\Requests\CourseEducatePlanSchedule\CourseEducatePlanScheduleRequest;
use App\Models\Api\CourseEducatePlanScheduleModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CourseEducatePlanScheduleController extends Controller
{
    protected $model = CourseEducatePlanScheduleModel::class;

    protected $request = CourseEducatePlanScheduleRequest::class;
}
