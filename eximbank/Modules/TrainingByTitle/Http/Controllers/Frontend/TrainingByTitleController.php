<?php

namespace Modules\TrainingByTitle\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Modules\TrainingByTitle\Entities\TrainingByTitleCategory;

class TrainingByTitleController extends Controller
{
    public function index(){
        $user = profile();
        $title = @$user->titles;
        //Thời gian bắt đầu của Lộ trình đào tạo
        if ($user->date_title_appointment) {
            $start_date = get_date($user->date_title_appointment, 'Y-m-d');
        } elseif ($user->effective_date) {
            $start_date = get_date($user->effective_date, 'Y-m-d');
        } elseif ($user->join_company) {
            $start_date = get_date($user->join_company, 'Y-m-d');
        } else {
            $start_date = get_date($user->created_at, 'Y-m-d');
        }

        $training_by_title_category = TrainingByTitleCategory::where('title_id', '=', @$title->id)->get();
        if (url_mobile()){
            return view('trainingbytitle::mobile.training_by_title', [
                'training_by_title_category' => $training_by_title_category,
                'start_date' => $start_date
            ]);
        }

        return redirect()->route('module.frontend.user.training_by_title');
    }
}
