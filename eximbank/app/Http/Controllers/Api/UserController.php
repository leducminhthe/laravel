<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Imanghafoori\MasterPass\Tests\Stubs\UserModel;

class UserController extends Controller
{
    protected $model = UserModel::class;
}
