<?php

namespace App\Http\Controllers\Api\FilterWord;

use App\Http\Requests\FilterWord\FilterWordRequest;
use App\Models\Api\FilterWordModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class FilterWordController extends Controller
{
    protected $model = FilterWordModel::class;

    protected $request = FilterWordRequest::class;
}
