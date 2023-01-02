<?php

namespace App\Http\Controllers\Api\UserContactOutside;

use App\Http\Requests\UserContactOutside\UserContactOutsideRequest;
use App\Models\Api\UserContactOutsideModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class UserContactOutsideController extends Controller
{
    protected $model = UserContactOutsideModel::class;

    protected $request = UserContactOutsideRequest::class;
}
