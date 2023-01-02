<?php

namespace App\Http\Controllers\Api\CommitGroup;

use App\Http\Requests\CommitGroup\CommitGroupRequest;
use App\Models\Api\CommitGroupModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class CommitGroupController extends Controller
{
    protected $model = CommitGroupModel::class;

    protected $request = CommitGroupRequest::class;
}
