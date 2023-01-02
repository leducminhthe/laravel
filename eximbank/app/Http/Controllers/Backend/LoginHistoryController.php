<?php

namespace App\Http\Controllers\Backend;

use App\Scopes\DraftScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories\Unit;
use App\Models\Categories\Titles;
use Illuminate\Support\Facades\DB;
use Modules\Report\Entities\BC18;

class LoginHistoryController extends Controller
{
    public function index() {        
        // return view('backend.login_history.index');
        return view('backend.history.index');
    }
}
