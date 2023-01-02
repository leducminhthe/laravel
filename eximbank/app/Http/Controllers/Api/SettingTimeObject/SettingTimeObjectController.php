<?php

namespace App\Http\Controllers\Api\SettingTimeObject;

use App\Http\Requests\SettingTimeObject\SettingTimeObjectRequest;
use App\Models\Api\SettingTimeObjectModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class SettingTimeObjectController extends Controller
{
    protected $model = SettingTimeObjectModel::class;

    protected $request = SettingTimeObjectRequest::class;
}
