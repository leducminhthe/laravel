<?php

namespace App\Http\Controllers\Api\Certificate;

use App\Http\Requests\Certificate\CertificateRequest;
use App\Models\Api\CertificateModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CertificateController extends Controller
{
    protected $model = CertificateModel::class;

    protected $request = CertificateRequest::class;
}
