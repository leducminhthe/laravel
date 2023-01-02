<?php

namespace App\Http\Controllers\Api\TrainingProgram;

use App\Http\Requests\TrainingProgram\TrainingProgramRequest;
use App\Models\Api\TrainingProgramModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Orion\Http\Controllers\RelationController;

class TrainingProgramSubjectController extends RelationController
{
    protected $model = TrainingProgramModel::class;

    protected $relation = 'subject';
}
