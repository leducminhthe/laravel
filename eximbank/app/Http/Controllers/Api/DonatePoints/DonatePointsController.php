<?php

namespace App\Http\Controllers\Api\DonatePoints;

use App\Http\Requests\DonatePoints\DonatePointsRequest;
use App\Models\Api\DonatePointsModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class DonatePointsController extends Controller
{
    protected $model = DonatePointsModel::class;

    protected $request = DonatePointsRequest::class;
}
