<?php

namespace App\Http\Controllers\Api\LevelSubject;

use App\Http\Requests\LevelSubject\LevelSubjectRequest;
use App\Models\Api\LevelSubjectModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class LevelSubjectController extends Controller
{
    protected $model = LevelSubjectModel::class;

    protected $request = LevelSubjectRequest::class;
}
