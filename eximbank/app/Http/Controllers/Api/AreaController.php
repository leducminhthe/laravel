<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Area\AreaCollection;
use App\Http\Resources\Area\AreaResource;
use App\Models\Api\AreaModel;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;

class AreaController extends Controller
{
    use DisableAuthorization;
    protected $model = AreaModel::class;

//    protected $resource = AreaResource::class;
    protected $collectionResource  = AreaCollection::class;
}
