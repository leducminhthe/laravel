<?php

namespace App\Http\Controllers\Api\ForumComment;

use App\Http\Requests\ForumComment\ForumCommentRequest;
use App\Models\Api\ForumCommentModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class ForumCommentController extends Controller
{
    protected $model = ForumCommentModel::class;

    protected $request = ForumCommentRequest::class;
}
