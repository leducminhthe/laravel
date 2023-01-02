<?php

namespace Modules\Quiz\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Quiz\Entities\QuizAttempts;

class FrontendController extends Controller
{
    public function index()
    {
        $user_id = profile()->user_id;
        $user_type = profile()->type_user;

        $query = DB::query();
        $query->select([
            'a.id',
            'a.quiz_type',
            'a.name AS quiz_name',
            'a.limit_time',
            'a.pass_score',
            'a.max_score',
            'a.img',
            'a.view_result',
            'b.id AS part_id',
            'b.start_date AS start_date',
            'b.end_date AS end_date',
        ]);
        $query->from('el_quiz AS a')
            ->join('el_quiz_part AS b', 'b.quiz_id', '=', 'a.id')
            ->where('a.status', '=', 1)
            ->where('a.is_open', '=', 1)
            ->where(function ($sub){
                $sub->orWhere('a.quiz_type', '=', 3);
                $sub->orWhereExists(function ($subquery){
                    $subquery->selectRaw(1)
                        ->from('el_offline_course')
                        ->whereNotNull('quiz_id')
                        ->where('status', '=', 1)
                        ->where('isopen', '=', 1)
                        ->whereColumn('quiz_id', '=', 'a.id');
                });
            })
            ->whereExists(function ($subquery2) use ($user_id, $user_type) {
                $subquery2->selectRaw(1)
                    ->from('el_quiz_register')
                    ->where('user_id', '=', $user_id)
                    ->where('type', '=', $user_type)
                    ->whereColumn('quiz_id', '=', 'a.id')
                    ->whereColumn('part_id', '=', 'b.id');
            });

        $quizs = $query->get();
        foreach($quizs as $item){
            $attempt = QuizAttempts::where('quiz_id', '=', $item->id)
            ->where('user_id', '=', $user_id)
            ->where('type', '=', $user_type)
            ->where('state', '=', 'inprogress')
            ->orderByDesc('id')->first();

            if (($item->view_result == 1) || $attempt || $item->start_date <= date('Y-m-d H:i:s')){
                $item->url_go_quiz = route('module.quiz_mobile.doquiz.index', ['quiz_id' => $item->id, 'part_id' => $item->part_id]);
            }else{
                $item->url_go_quiz = '#';
            }
        }

        return view('themes.mobile.frontend.quiz.index', [
            'quizs' => $quizs,
            'user_id' => $user_id,
            'user_type' => $user_type,
        ]);
    }
}
