<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class NightModeController extends Controller
{
    public function settingNightMode(Request $request) {
        if (session()->get('nightMode') == 1) {
            session(['nightMode' => 0]);
            session()->save();
            $setting = 0;
        } else {
            session(['nightMode' => 1]);
            session()->save();
            $setting = 1;
        }

        json_result(['setting' => $setting]);
    }
}
