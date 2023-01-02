<?php

namespace App\Http\Controllers\Api\DailyTrainingSettingScoreViews;

use App\Http\Requests\DailyTrainingSettingScoreViews\DailyTrainingSettingScoreViewsRequest;
use App\Models\Api\DailyTrainingSettingScoreViewsModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingSettingScoreViewsController extends Controller
{
    protected $model = DailyTrainingSettingScoreViewsModel::class;

    protected $request = DailyTrainingSettingScoreViewsRequest::class;
}
