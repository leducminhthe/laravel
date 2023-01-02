<?php

namespace App\Http\Controllers\Api\ForumUserLikeComment;

use App\Http\Requests\ForumUserLikeComment\ForumUserLikeCommentRequest;
use App\Models\Api\ForumUserLikeCommentModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class ForumUserLikeCommentController extends Controller
{
    protected $model = ForumUserLikeCommentModel::class;

    protected $request = ForumUserLikeCommentRequest::class;
}
