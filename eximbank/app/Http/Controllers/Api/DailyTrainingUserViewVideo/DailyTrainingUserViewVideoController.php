<?php

namespace App\Http\Controllers\Api\DailyTrainingUserViewVideo;

use App\Http\Requests\DailyTrainingUserViewVideo\DailyTrainingUserViewVideoRequest;
use App\Models\Api\DailyTrainingUserViewVideoModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingUserViewVideoController extends Controller
{
    protected $model = DailyTrainingUserViewVideoModel::class;

    protected $request = DailyTrainingUserViewVideoRequest::class;
}
