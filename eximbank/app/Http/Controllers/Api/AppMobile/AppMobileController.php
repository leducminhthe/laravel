<?php

namespace App\Http\Controllers\Api\AppMobile;

use App\Http\Requests\AppMobile\AppMobileRequest;
use App\Models\Api\AppMobileModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class AppMobileController extends Controller
{
    protected $model = AppMobileModel::class;

    protected $request = AppMobileRequest::class;
}
