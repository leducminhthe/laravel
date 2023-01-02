<?php

namespace App\Http\Controllers\Api\Slider;

use App\Http\Requests\Slider\SliderRequest;
use App\Models\Api\SliderModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class SliderController extends Controller
{
    protected $model = SliderModel::class;

    protected $request = SliderRequest::class;
}
