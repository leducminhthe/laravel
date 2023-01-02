<?php

namespace App\Http\Controllers\Api\Note;

use App\Http\Requests\Note\NoteRequest;
use App\Models\Api\NoteModel;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller;

class NoteController extends Controller
{
    protected $model = NoteModel::class;

    protected $request = NoteRequest::class;
}
