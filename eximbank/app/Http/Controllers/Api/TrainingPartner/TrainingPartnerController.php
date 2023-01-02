<?php

namespace App\Http\Controllers\Api\TrainingPartner;

use App\Http\Requests\TrainingPartner\TrainingPartnerRequest;
use App\Models\Api\TrainingPartnerModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class TrainingPartnerController extends Controller
{
    protected $model = TrainingPartnerModel::class;

    protected $request = TrainingPartnerRequest::class;
}
