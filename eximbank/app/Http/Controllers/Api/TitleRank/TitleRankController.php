<?php

namespace App\Http\Controllers\Api\TitleRank;

use App\Http\Requests\TitleRank\TitleRankRequest;
use App\Models\Api\TitleRankModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TitleRankController extends Controller
{
    protected $model = TitleRankModel::class;

    protected $request = TitleRankRequest::class;
}
