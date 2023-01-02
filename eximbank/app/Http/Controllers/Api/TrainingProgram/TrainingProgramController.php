<?php

namespace App\Http\Controllers\Api\TrainingProgram;

use App\Http\Requests\TrainingProgram\TrainingProgramRequest;
use App\Models\Api\TrainingProgramModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;

class TrainingProgramController extends Controller
{
    use DisableAuthorization;
    protected $model = TrainingProgramModel::class;

    protected $request = TrainingProgramRequest::class;
}
