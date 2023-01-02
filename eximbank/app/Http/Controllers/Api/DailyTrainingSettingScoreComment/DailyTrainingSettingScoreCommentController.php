<?php

namespace App\Http\Controllers\Api\DailyTrainingSettingScoreComment;

use App\Http\Requests\DailyTrainingSettingScoreComment\DailyTrainingSettingScoreCommentRequest;
use App\Models\Api\DailyTrainingSettingScoreCommentModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DailyTrainingSettingScoreCommentController extends Controller
{
    protected $model = DailyTrainingSettingScoreCommentModel::class;

    protected $request = DailyTrainingSettingScoreCommentRequest::class;
}
