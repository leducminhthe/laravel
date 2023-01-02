<?php

namespace App\Http\Controllers\Api\SliderOutside;

use App\Http\Requests\SliderOutside\SliderOutsideRequest;
use App\Models\Api\SliderOutsideModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class SliderOutsideController extends Controller
{
    protected $model = SliderOutsideModel::class;

    protected $request = SliderOutsideRequest::class;
}
