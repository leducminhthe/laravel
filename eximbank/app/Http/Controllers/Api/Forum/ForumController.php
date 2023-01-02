<?php

namespace App\Http\Controllers\Api\Forum;

use App\Http\Requests\Forum\ForumRequest;
use App\Models\Api\ForumModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class ForumController extends Controller
{
    protected $model = ForumModel::class;

    protected $request = ForumRequest::class;
}
