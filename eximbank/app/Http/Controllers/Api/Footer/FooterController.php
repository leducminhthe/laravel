<?php

namespace App\Http\Controllers\Api\Footer;

use App\Http\Requests\Footer\FooterRequest;
use App\Models\Api\FooterModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class FooterController extends Controller
{
    protected $model = FooterModel::class;

    protected $request = FooterRequest::class;
}
