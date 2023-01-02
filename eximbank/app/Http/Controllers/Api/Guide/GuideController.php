<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Requests\Guide\GuideRequest;
use App\Models\Api\GuideModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class GuideController extends Controller
{
    protected $model = GuideModel::class;

    protected $request = GuideRequest::class;
}
