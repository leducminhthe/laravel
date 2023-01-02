<?php

namespace App\Http\Controllers\Api\TrainingObject;

use App\Http\Requests\TrainingObject\TrainingObjectRequest;
use App\Models\Api\TrainingObjectModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TrainingObjectController extends Controller
{
    protected $model = TrainingObjectModel::class;

    protected $request = TrainingObjectRequest::class;
}
