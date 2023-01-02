<?php

namespace App\Http\Controllers\Api\MailSignature;

use App\Http\Requests\MailSignature\MailSignatureRequest;
use App\Models\Api\MailSignatureModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class MailSignatureController extends Controller
{
    protected $model = MailSignatureModel::class;

    protected $request = MailSignatureRequest::class;
}
