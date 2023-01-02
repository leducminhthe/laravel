<?php

namespace App\Http\Controllers\Api\ForumCategory;

use App\Http\Requests\ForumCategory\ForumCategoryRequest;
use App\Models\Api\ForumCategoryModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Orion\Http\Controllers\RelationController;

class ForumCategoryForumController extends RelationController
{
    protected $model = ForumCategoryModel::class;

    protected $relation = 'forum';
}
