<?php

namespace App\Http\Controllers\Api\UnitTypeCode;

use App\Http\Requests\UnitTypeCode\UnitTypeCodeRequest;
use App\Models\Api\UnitTypeCodeModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class UnitTypeCodeController extends Controller
{
    protected $model = UnitTypeCodeModel::class;

    protected $request = UnitTypeCodeRequest::class;
}
