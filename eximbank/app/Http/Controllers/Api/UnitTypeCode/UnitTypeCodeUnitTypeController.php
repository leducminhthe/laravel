<?php

namespace App\Http\Controllers\Api\UnitTypeCode;

use App\Models\Api\UnitTypeCodeModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\RelationController;

class UnitTypeCodeUnitTypeController extends RelationController
{
    protected $model = UnitTypeCodeModel::class;

    protected $relation = 'unit_type';
}
