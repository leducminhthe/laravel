<?php

namespace App\Http\Controllers\Api\DailyTrainingUserLikeVideo;

use App\Http\Requests\DailyTrainingUserLikeVideo\DailyTrainingUserLikeVideoRequest;
use App\Models\Api\DailyTrainingUserLikeVideoModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingUserLikeVideoController extends Controller
{
    protected $model = DailyTrainingUserLikeVideoModel::class;

    protected $request = DailyTrainingUserLikeVideoRequest::class;
}
