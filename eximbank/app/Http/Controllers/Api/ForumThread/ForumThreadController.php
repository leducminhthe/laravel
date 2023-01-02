<?php

namespace App\Http\Controllers\Api\ForumThread;

use App\Http\Requests\ForumThread\ForumThreadRequest;
use App\Models\Api\ForumThreadModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class ForumThreadController extends Controller
{
    protected $model = ForumThreadModel::class;

    protected $request = ForumThreadRequest::class;
}
