<?php

namespace App\Http\Controllers\Api\CourseEducatePlanCost;

use App\Http\Requests\CourseEducatePlanCost\CourseEducatePlanCostRequest;
use App\Models\Api\CourseEducatePlanCostModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CourseEducatePlanCostController extends Controller
{
    protected $model = CourseEducatePlanCostModel::class;

    protected $request = CourseEducatePlanCostRequest::class;
}
