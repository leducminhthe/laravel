<?php

namespace App\Http\Controllers\Api\Province;

use App\Http\Requests\Province\ProvinceRequest;
use App\Models\Api\ProvinceModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Orion\Http\Controllers\RelationController;

class ProvinceController extends Controller
{
    protected $model = ProvinceModel::class;

    protected $request = ProvinceRequest::class;
}
