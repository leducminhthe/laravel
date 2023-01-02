<?php

namespace App\Http\Controllers\Api\Discipline;

use App\Http\Requests\Discipline\DisciplineRequest;
use App\Models\Api\DisciplineModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DisciplineController extends Controller
{
    protected $model = DisciplineModel::class;

    protected $request = DisciplineRequest::class;
}
