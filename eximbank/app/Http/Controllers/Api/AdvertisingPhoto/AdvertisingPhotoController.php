<?php

namespace App\Http\Controllers\Api\AdvertisingPhoto;

use App\Http\Requests\AdvertisingPhoto\AdvertisingPhotoRequest;
use App\Models\Api\AdvertisingPhotoModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class AdvertisingPhotoController extends Controller
{
    protected $model = AdvertisingPhotoModel::class;

    protected $request = AdvertisingPhotoRequest::class;
}
