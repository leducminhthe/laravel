<?php

namespace App\Http\Controllers\Api\Logo;

use App\Http\Requests\Logo\LogoRequest;
use App\Models\Api\LogoModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class LogoController extends Controller
{
    protected $model = LogoModel::class;

    protected $request = LogoRequest::class;
}
