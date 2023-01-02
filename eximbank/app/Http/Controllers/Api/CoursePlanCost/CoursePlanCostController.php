<?php

namespace App\Http\Controllers\Api\CoursePlanCost;

use App\Http\Requests\CoursePlanCost\CoursePlanCostRequest;
use App\Models\Api\CoursePlanCostModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CoursePlanCostController extends Controller
{
    protected $model = CoursePlanCostModel::class;

    protected $request = CoursePlanCostRequest::class;
}
