<?php

namespace App\Http\Controllers\Api\TrainingForm;

use App\Http\Requests\TrainingForm\TrainingFormRequest;
use App\Models\Api\TrainingFormModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TrainingFormController extends Controller
{
    protected $model = TrainingFormModel::class;

    protected $request = TrainingFormRequest::class;
}
