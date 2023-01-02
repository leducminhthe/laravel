<?php

namespace App\Http\Controllers\Api\ForumCategoryPermission;

use App\Http\Requests\ForumCategoryPermission\ForumCategoryPermissionRequest;
use App\Models\Api\ForumCategoryPermissionModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class ForumCategoryPermissionController extends Controller
{
    protected $model = ForumCategoryPermissionModel::class;

    protected $request = ForumCategoryPermissionRequest::class;
}
