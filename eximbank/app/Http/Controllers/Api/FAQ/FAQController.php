<?php

namespace App\Http\Controllers\Api\FAQ;

use App\Http\Requests\FAQ\FAQRequest;
use App\Models\Api\FAQModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class FAQController extends Controller
{
    protected $model = FAQModel::class;

    protected $request = FAQRequest::class;
}
