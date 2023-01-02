<?php

namespace App\Http\Controllers\Api\Absent;

use App\Http\Requests\Absent\AbsentRequest;
use App\Models\Api\AbsentModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;

class AbsentController extends Controller
{
    protected $model = AbsentModel::class;

    protected $request = AbsentRequest::class;
}
