<?php

namespace App\Http\Controllers\Api\Contact;

use App\Http\Requests\Contact\ContactRequest;
use App\Models\Api\ContactModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class ContactController extends Controller
{
    protected $model = ContactModel::class;

    protected $request = ContactRequest::class;
}
