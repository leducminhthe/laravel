<?php

namespace App\Http\Controllers\Api\CareerRoadmapUser;

use App\Http\Requests\CareerRoadmapUser\CareerRoadmapUserRequest;
use App\Models\Api\CareerRoadmapUserModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;
use Orion\Http\Controllers\RelationController;

class CareerRoadmapUserCareerRoadmapTitleUserController extends RelationController
{
    protected $model = CareerRoadmapUserModel::class;

    protected $relation = 'career_roadmap_titles_user';
}
