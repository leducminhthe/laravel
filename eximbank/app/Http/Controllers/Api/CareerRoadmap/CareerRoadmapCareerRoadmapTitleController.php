<?php

namespace App\Http\Controllers\Api\CareerRoadmap;

use App\Models\Api\CareerRoadmapModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\RelationController;

class CareerRoadmapCareerRoadmapTitleController extends RelationController
{
    protected $model = CareerRoadmapModel::class;

    protected $relation = 'career_roadmap_titles';
}
