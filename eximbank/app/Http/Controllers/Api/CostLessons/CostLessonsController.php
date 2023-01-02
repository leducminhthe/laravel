<?php

namespace App\Http\Controllers\Api\CostLessons;

use App\Http\Requests\CostLessons\CostLessonsRequest;
use App\Models\Api\CostLessonsModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CostLessonsController extends Controller
{
    protected $model = CostLessonsModel::class;

    protected $request = CostLessonsRequest::class;
}
