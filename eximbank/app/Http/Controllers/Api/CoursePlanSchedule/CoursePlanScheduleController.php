<?php

namespace App\Http\Controllers\Api\CoursePlanSchedule;

use App\Http\Requests\CoursePlanSchedule\CoursePlanScheduleRequest;
use App\Models\Api\CoursePlanScheduleModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CoursePlanScheduleController extends Controller
{
    protected $model = CoursePlanScheduleModel::class;

    protected $request = CoursePlanScheduleRequest::class;
}
