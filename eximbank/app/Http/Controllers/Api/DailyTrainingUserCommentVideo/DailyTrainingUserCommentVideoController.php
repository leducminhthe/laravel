<?php

namespace App\Http\Controllers\Api\DailyTrainingUserCommentVideo;

use App\Http\Requests\DailyTrainingUserCommentVideo\DailyTrainingUserCommentVideoRequest;
use App\Models\Api\DailyTrainingUserCommentVideoModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingUserCommentVideoController extends Controller
{
    protected $model = DailyTrainingUserCommentVideoModel::class;

    protected $request = DailyTrainingUserCommentVideoRequest::class;
}
