<?php

namespace App\Http\Controllers\Api\TrainingType;

use App\Http\Requests\TrainingType\TrainingTypeRequest;
use App\Models\Api\TrainingTypeModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TrainingTypeController extends Controller
{
    protected $model = TrainingTypeModel::class;

    protected $request = TrainingTypeRequest::class;
}
