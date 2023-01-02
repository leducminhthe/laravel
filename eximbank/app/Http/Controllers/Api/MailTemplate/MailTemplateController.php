<?php

namespace App\Http\Controllers\Api\MailTemplate;

use App\Http\Requests\MailTemplate\MailTemplateRequest;
use App\Models\Api\MailTemplateModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class MailTemplateController extends Controller
{
    protected $model = MailTemplateModel::class;

    protected $request = MailTemplateRequest::class;
}
