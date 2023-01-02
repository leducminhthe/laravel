<?php

namespace App\Http\Controllers\Api\DailyTrainingCategory;

use App\Http\Requests\DailyTrainingCategory\DailyTrainingCategoryRequest;
use App\Models\Api\DailyTrainingCategoryModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingCategoryController extends Controller
{
    protected $model = DailyTrainingCategoryModel::class;

    protected $request = DailyTrainingCategoryRequest::class;
}
