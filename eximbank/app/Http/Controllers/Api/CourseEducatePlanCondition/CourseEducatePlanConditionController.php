<?php

namespace App\Http\Controllers\Api\CourseEducatePlanCondition;

use App\Http\Requests\CourseEducatePlanCondition\CourseEducatePlanConditionRequest;
use App\Models\Api\CourseEducatePlanConditionModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CourseEducatePlanConditionController extends Controller
{
    protected $model = CourseEducatePlanConditionModel::class;

    protected $request = CourseEducatePlanConditionRequest::class;
}
