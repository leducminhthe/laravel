<?php

namespace App\Http\Controllers\Api\DailyTrainingVideo;

use App\Http\Requests\DailyTrainingVideo\DailyTrainingVideoRequest;
use App\Models\Api\DailyTrainingVideoModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingVideoController extends Controller
{
    protected $model = DailyTrainingVideoModel::class;

    protected $request = DailyTrainingVideoRequest::class;
}
