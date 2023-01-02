<?php

namespace App\Http\Controllers\Api\PermissionType;

use App\Http\Requests\PermissionType\PermissionTypeRequest;
use App\Models\Api\PermissionTypeModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class PermissionTypeController extends Controller
{
    protected $model = PermissionTypeModel::class;

    protected $request = PermissionTypeRequest::class;
}
