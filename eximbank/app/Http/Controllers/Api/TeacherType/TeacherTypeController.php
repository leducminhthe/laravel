<?php

namespace App\Http\Controllers\Api\TeacherType;

use App\Http\Requests\TeacherType\TeacherTypeRequest;
use App\Models\Api\TeacherTypeModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TeacherTypeController extends Controller
{
    protected $model = TeacherTypeModel::class;

    protected $request = TeacherTypeRequest::class;
}
