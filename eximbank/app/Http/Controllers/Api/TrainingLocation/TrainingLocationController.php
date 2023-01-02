<?php

namespace App\Http\Controllers\Api\TrainingLocation;

use App\Http\Requests\TrainingLocation\TrainingLocationRequest;
use App\Models\Api\TrainingLocationModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TrainingLocationController extends Controller
{
    protected $model = TrainingLocationModel::class;

    protected $request = TrainingLocationRequest::class;
}
