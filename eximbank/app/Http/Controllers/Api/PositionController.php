<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\PositionModel;
use Illuminate\Http\Request;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;

class PositionController extends Controller
{
    use DisableAuthorization;
    protected $model = PositionModel::class;
}
