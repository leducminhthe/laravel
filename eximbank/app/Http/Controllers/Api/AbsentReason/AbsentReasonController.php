<?php

namespace App\Http\Controllers\Api\AbsentReason;

use App\Http\Requests\AbsentReason\AbsentReasonRequest;
use App\Models\Api\AbsentReasonModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class AbsentReasonController extends Controller
{
    protected $model = AbsentReasonModel::class;

    protected $request = AbsentReasonRequest::class;
}
