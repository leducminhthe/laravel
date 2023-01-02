<?php

namespace App\Http\Controllers\Api\CareerRoadmapTitle;

use App\Http\Requests\CareerRoadmapTitle\CareerRoadmapTitleRequest;
use App\Models\Api\CareerRoadmapTitleModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CareerRoadmapTitleController extends Controller
{
    protected $model = CareerRoadmapTitleModel::class;

    protected $request = CareerRoadmapTitleRequest::class;
}
