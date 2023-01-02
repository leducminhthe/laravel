<?php

namespace App\Http\Controllers\Api\DailyTrainingUserLikeCommentVideo;

use App\Http\Requests\DailyTrainingUserLikeCommentVideo\DailyTrainingUserLikeCommentVideoRequest;
use App\Models\Api\DailyTrainingUserLikeCommentVideoModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingUserLikeCommentVideoController extends Controller
{
    protected $model = DailyTrainingUserLikeCommentVideoModel::class;

    protected $request = DailyTrainingUserLikeCommentVideoRequest::class;
}
