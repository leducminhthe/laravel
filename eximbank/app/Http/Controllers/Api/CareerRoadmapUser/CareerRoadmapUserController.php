<?php

namespace App\Http\Controllers\Api\CareerRoadmapUser;

use App\Http\Requests\CareerRoadmapUser\CareerRoadmapUserRequest;
use App\Models\Api\CareerRoadmapUserModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CareerRoadmapUserController extends Controller
{
    protected $model = CareerRoadmapUserModel::class;

    protected $request = CareerRoadmapUserRequest::class;
}
