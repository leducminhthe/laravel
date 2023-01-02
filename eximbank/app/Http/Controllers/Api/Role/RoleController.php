<?php

namespace App\Http\Controllers\Api\Role;

use App\Http\Requests\Role\RoleRequest;
use App\Models\Api\RoleModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class RoleController extends Controller
{
    protected $model = RoleModel::class;

    protected $request = RoleRequest::class;
}
