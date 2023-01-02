<?php

namespace App\Http\Controllers\Api\CourseOld;

use App\Http\Requests\CourseOld\CourseOldRequest;
use App\Models\Api\CourseOldModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CourseOldController extends Controller
{
    protected $model = CourseOldModel::class;

    protected $request = CourseOldRequest::class;
}
