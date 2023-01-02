<?php

namespace App\Http\Controllers\Api\CoursePlan;

use App\Http\Requests\CoursePlan\CoursePlanRequest;
use App\Models\Api\CoursePlanModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CoursePlanController extends Controller
{
    protected $model = CoursePlanModel::class;

    protected $request = CoursePlanRequest::class;
}
