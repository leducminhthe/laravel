<?php

namespace App\Http\Controllers\Api\InfomationCompany;

use App\Http\Requests\InfomationCompany\InfomationCompanyRequest;
use App\Models\Api\InfomationCompanyModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class InfomationCompanyController extends Controller
{
    protected $model = InfomationCompanyModel::class;

    protected $request = InfomationCompanyRequest::class;
}
