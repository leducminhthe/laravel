<?php

namespace App\Http\Controllers\Api\Feedback;

use App\Http\Requests\Feedback\FeedbackRequest;
use App\Models\Api\FeedbackModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class FeedbackController extends Controller
{
    protected $model = FeedbackModel::class;

    protected $request = FeedbackRequest::class;
}
