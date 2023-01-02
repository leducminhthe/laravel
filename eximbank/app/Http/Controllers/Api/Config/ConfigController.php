<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Requests\Config\ConfigRequest;
use App\Models\Api\ConfigModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class ConfigController extends Controller
{
    protected $model = ConfigModel::class;

    protected $request = ConfigRequest::class;
}
