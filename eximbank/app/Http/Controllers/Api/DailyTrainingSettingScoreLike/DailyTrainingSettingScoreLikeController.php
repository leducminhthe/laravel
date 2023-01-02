<?php

namespace App\Http\Controllers\Api\DailyTrainingSettingScoreLike;

use App\Http\Requests\DailyTrainingSettingScoreLike\DailyTrainingSettingScoreLikeRequest;
use App\Models\Api\DailyTrainingSettingScoreLikeModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingSettingScoreLikeController extends Controller
{
    protected $model = DailyTrainingSettingScoreLikeModel::class;

    protected $request = DailyTrainingSettingScoreLikeRequest::class;
}
