<?php

namespace App\Http\Controllers\Api\TrainingTeacher;

use App\Http\Requests\TrainingTeacher\TrainingTeacherRequest;
use App\Models\Api\TrainingTeacherModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TrainingTeacherController extends Controller
{
    protected $model = TrainingTeacherModel::class;

    protected $request = TrainingTeacherRequest::class;
}
