<?php

namespace App\Http\Controllers\Api\Boxmap;

use App\Http\Requests\Boxmap\BoxmapRequest;
use App\Models\Api\BoxmapModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class BoxmapController extends Controller
{
    protected $model = BoxmapModel::class;

    protected $request = BoxmapRequest::class;
}
