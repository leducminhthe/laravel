<?php

namespace App\Http\Controllers\Api\Cert;

use App\Http\Requests\Cert\CertRequest;
use App\Models\Api\CertModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CertController extends Controller
{
    protected $model = CertModel::class;

    protected $request = CertRequest::class;
}
