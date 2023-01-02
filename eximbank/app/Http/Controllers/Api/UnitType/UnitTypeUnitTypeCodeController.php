<?php

namespace App\Http\Controllers\Api\UnitType;

use App\Http\Requests\UnitTypeCode\UnitTypeCodeRequest;
use App\Models\Api\UnitTypeModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\RelationController;

class UnitTypeUnitTypeCodeController extends RelationController
{
    protected $model = UnitTypeModel::class;

    protected $request = UnitTypeCodeRequest::class;

    protected $relation = 'unit_type_code';
}
