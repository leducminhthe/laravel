<?php

namespace App\Http\Controllers\Api\CourseEducatePlan;

use App\Http\Requests\CourseEducatePlan\CourseEducatePlanRequest;
use App\Models\Api\CourseEducatePlanModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CourseEducatePlanController extends Controller
{
    protected $model = CourseEducatePlanModel::class;

    protected $request = CourseEducatePlanRequest::class;
}
