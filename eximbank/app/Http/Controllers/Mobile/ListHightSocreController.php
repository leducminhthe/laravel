<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Categories\TrainingTeacher;
use Illuminate\Support\Facades\DB;

class ListHightSocreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $search = $request->search;

        $training_teacher = TrainingTeacher::whereNotNull('user_id')->pluck('user_id')->toArray();
        $query = DB::query();
        $query->select(['profile.user_id', 'user_point.point']);
        $query->from('el_promotion_user_point as user_point');
        $query->leftJoin('el_profile_view as profile', 'user_point.user_id', '=', 'profile.user_id');
        $query->where('profile.status_id', '=', 1);
        $query->where('profile.user_id', '>', 2);
        $query->where('user_point.point', '>', 0);
        $query->whereNotIn('profile.user_id', $training_teacher);
        $query->orderBy('user_point.point', 'DESC');

        if($search) {
            $query->where('profile.full_name', 'like', '%'. $search .'%');
        }

        $user = $query->paginate(20);
        return view('themes.mobile.frontend.list_hight_score.index', [
            'user' => $user,
        ]);
    }
}
