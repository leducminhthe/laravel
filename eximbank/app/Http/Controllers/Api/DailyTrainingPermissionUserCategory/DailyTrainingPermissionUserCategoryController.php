<?php

namespace App\Http\Controllers\Api\DailyTrainingPermissionUserCategory;

use App\Http\Requests\DailyTrainingPermissionUserCategory\DailyTrainingPermissionUserCategoryRequest;
use App\Models\Api\DailyTrainingPermissionUserCategoryModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingPermissionUserCategoryController extends Controller
{
    protected $model = DailyTrainingPermissionUserCategoryModel::class;

    protected $request = DailyTrainingPermissionUserCategoryRequest::class;
}
