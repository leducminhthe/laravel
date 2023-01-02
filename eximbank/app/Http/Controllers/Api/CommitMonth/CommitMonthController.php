<?php

namespace App\Http\Controllers\Api\CommitMonth;

use App\Http\Requests\CommitMonth\CommitMonthRequest;
use App\Models\Api\CommitMonthModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CommitMonthController extends Controller
{
    protected $model = CommitMonthModel::class;

    protected $request = CommitMonthRequest::class;
}
