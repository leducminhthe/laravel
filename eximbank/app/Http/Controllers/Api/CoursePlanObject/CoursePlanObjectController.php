<?php

namespace App\Http\Controllers\Api\CoursePlanObject;

use App\Http\Requests\CoursePlanObject\CoursePlanObjectRequest;
use App\Models\Api\CoursePlanObjectModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CoursePlanObjectController extends Controller
{
    protected $model = CoursePlanObjectModel::class;

    protected $request = CoursePlanObjectRequest::class;
}
