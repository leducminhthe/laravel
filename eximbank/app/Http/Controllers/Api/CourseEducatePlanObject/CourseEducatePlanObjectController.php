<?php

namespace App\Http\Controllers\Api\CourseEducatePlanObject;

use App\Http\Requests\CourseEducatePlanObject\CourseEducatePlanObjectRequest;
use App\Models\Api\CourseEducatePlanObjectModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CourseEducatePlanObjectController extends Controller
{
    protected $model = CourseEducatePlanObjectModel::class;

    protected $request = CourseEducatePlanObjectRequest::class;
}
