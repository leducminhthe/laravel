<?php

namespace App\Http\Controllers\Api\CareerRoadmap;

use App\Http\Requests\CareerRoadmap\CareerRoadmapRequest;
use App\Models\Api\CareerRoadmapModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CareerRoadmapController extends Controller
{
    protected $model = CareerRoadmapModel::class;

    protected $request = CareerRoadmapRequest::class;
}
