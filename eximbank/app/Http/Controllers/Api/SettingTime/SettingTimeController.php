<?php

namespace App\Http\Controllers\Api\SettingTime;

use App\Http\Requests\SettingTime\SettingTimeRequest;
use App\Models\Api\SettingTimeModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class SettingTimeController extends Controller
{
    protected $model = SettingTimeModel::class;

    protected $request = SettingTimeRequest::class;
}
