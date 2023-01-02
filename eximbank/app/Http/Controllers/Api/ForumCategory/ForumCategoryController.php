<?php

namespace App\Http\Controllers\Api\ForumCategory;

use App\Http\Requests\ForumCategory\ForumCategoryRequest;
use App\Models\Api\ForumCategoryModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class ForumCategoryController extends Controller
{
    protected $model = ForumCategoryModel::class;

    protected $request = ForumCategoryRequest::class;
}
