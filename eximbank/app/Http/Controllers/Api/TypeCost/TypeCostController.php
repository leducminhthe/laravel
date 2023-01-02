<?php

namespace App\Http\Controllers\Api\TypeCost;

use App\Http\Requests\TypeCost\TypeCostRequest;
use App\Models\Api\TypeCostModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TypeCostController extends Controller
{
    protected $model = TypeCostModel::class;

    protected $request = TypeCostRequest::class;
}
