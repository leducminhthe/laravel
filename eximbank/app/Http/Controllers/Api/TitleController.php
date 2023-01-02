<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\TitleModel;
use Illuminate\Http\Request;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;

class TitleController extends Controller
{
    use DisableAuthorization;
    protected $model = TitleModel::class;
    public function searchableBy() : array
    {
        return ['code', 'name'];
    }
    public function sortableBy(): array {
        return ['code','name'];
    }
}
