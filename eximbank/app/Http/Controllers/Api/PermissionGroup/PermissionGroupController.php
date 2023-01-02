<?php

namespace App\Http\Controllers\Api\PermissionGroup;

use App\Http\Requests\PermissionGroup\PermissionGroupRequest;
use App\Models\Api\PermissionGroupModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class PermissionGroupController extends Controller
{
    protected $model = PermissionGroupModel::class;

    protected $request = PermissionGroupRequest::class;
}
