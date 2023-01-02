<?php

namespace App\Http\Controllers\Api\District;

use App\Http\Requests\District\DistrictRequest;
use App\Models\Api\DistrictModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Orion\Http\Controllers\RelationController;

class DistrictProvinceController extends RelationController
{
    protected $model = DistrictModel::class;

    protected $relation = 'province';
}
