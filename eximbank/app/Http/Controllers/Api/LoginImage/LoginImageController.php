<?php

namespace App\Http\Controllers\Api\LoginImage;

use App\Http\Requests\LoginImage\LoginImageRequest;
use App\Models\Api\LoginImageModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class LoginImageController extends Controller
{
    protected $model = LoginImageModel::class;

    protected $request = LoginImageRequest::class;
}
