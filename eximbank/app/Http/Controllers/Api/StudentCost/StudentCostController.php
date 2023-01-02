<?php

namespace App\Http\Controllers\Api\StudentCost;

use App\Http\Requests\StudentCost\StudentCostRequest;
use App\Models\Api\StudentCostModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class StudentCostController extends Controller
{
    protected $model = StudentCostModel::class;

    protected $request = StudentCostRequest::class;
}
