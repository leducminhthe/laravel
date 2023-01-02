<?php

namespace App\Http\Controllers\Api\CareerRoadmapTitleUser;

use App\Http\Requests\CareerRoadmapTitleUser\CareerRoadmapTitleUserRequest;
use App\Models\Api\CareerRoadmapTitleUserModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CareerRoadmapTitleUserController extends Controller
{
    protected $model = CareerRoadmapTitleUserModel::class;

    protected $request = CareerRoadmapTitleUserRequest::class;
}
