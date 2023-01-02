<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Profile\ProfileCollection;
use App\Models\Api\ProfileModel;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;

class ProfileController extends Controller
{
    use DisableAuthorization;
    protected $model = ProfileModel::class;
}
