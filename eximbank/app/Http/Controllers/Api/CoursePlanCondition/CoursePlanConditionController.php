<?php

namespace App\Http\Controllers\Api\CoursePlanCondition;

use App\Http\Requests\CoursePlanCondition\CoursePlanConditionRequest;
use App\Models\Api\CoursePlanConditionModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CoursePlanConditionController extends Controller
{
    protected $model = CoursePlanConditionModel::class;

    protected $request = CoursePlanConditionRequest::class;
}
