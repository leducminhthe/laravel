<?php

namespace App\Http\Controllers\Backend;

use App\Models\Categories\Area;
use App\Models\Categories\Unit;
use App\Http\Controllers\Controller;
use App\Models\ProfileView;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {

        $max_level = Unit::getMaxUnitLevel();
        $level_name = function ($level) {
            return Unit::getLevelName($level);
        };

        $max_level_area = Area::getMaxAreaLevel();
        $level_name_area = function ($level){
            return Area::getLangName($level);
        };
        return view('backend.category.index', [
            'max_level' => $max_level,
            'level_name' => $level_name,
            'max_level_area' => $max_level_area,
            'level_name_area' => $level_name_area,
        ]);
    }

    public function dashboard() {
        return redirect()->route('module.dashboard');
    }

    public function getUserCreateUpdated(Request $request){
        if($request->created) {
            $user = ProfileView::where('user_id',$request->created)->first();
        } else {
            $user = ProfileView::where('user_id',$request->updated)->first();
        }

        return view('backend.modal.modal_user_created_updated', [
            'user' => $user,
        ]);
    }

    public function getUserInfo(Request $request){
        $created_at = $request->created_at ? get_date($request->created_at, 'd/m/Y H:i:s') : '';
        $updated_at = $request->updated_at ? get_date($request->updated_at, 'd/m/Y H:i:s') : '';
        if($request->created) {
            $user_create = ProfileView::where('user_id',$request->created)->first(['full_name','code','title_name','unit_name']);
        } 
        if($request->updated) {
            $user_update = ProfileView::where('user_id',$request->updated)->first(['full_name','code','title_name','unit_name']);
        }
        return view('backend.modal.modal_user_info', [
            'user_create' => $user_create,
            'user_update' => $user_update,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ]);
    }
}
