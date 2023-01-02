<?php

namespace App\Http\Controllers\Api\TrainingCost;

use App\Http\Requests\TrainingCost\TrainingCostRequest;
use App\Models\Api\TrainingCostModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TrainingCostController extends Controller
{
    protected $model = TrainingCostModel::class;

    protected $request = TrainingCostRequest::class;
}
